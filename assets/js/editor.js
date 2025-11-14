/**
 * Code Editor Integration
 * Handles CodeMirror initialization and code execution
 */

// Store CodeMirror instances
const editors = {};

// Initialize CodeMirror editors
document.addEventListener('DOMContentLoaded', function () {
    const textareas = document.querySelectorAll('.code-editor');

    // Check if any Python editors exist
    let hasPython = false;

    textareas.forEach(textarea => {
        const language = textarea.dataset.language;
        let mode = 'python';

        if (language === 'python') {
            hasPython = true;
        } else if (language === 'javascript') {
            mode = 'javascript';
        } else if (language === 'cpp') {
            mode = 'text/x-c++src';
        }

        const editor = CodeMirror.fromTextArea(textarea, {
            mode: mode,
            theme: 'monokai',
            lineNumbers: true,
            indentUnit: 4,
            tabSize: 4,
            indentWithTabs: false,
            lineWrapping: true,
            autoCloseBrackets: true,
            matchBrackets: true
        });

        // Store editor instance
        const exerciseId = textarea.id.replace('code-editor-', '');
        editors[exerciseId] = editor;
    });

    // Preload Pyodide if Python exercises exist
    if (hasPython && typeof loadPyodide !== 'undefined') {
        console.log('Preloading Python environment...');
        initPyodide().then(() => {
            console.log('Python environment ready!');
        }).catch(err => {
            console.error('Failed to preload Python:', err);
        });
    }
    
    // Setup AI button event listeners
    setupAIButtons();
});

/**
 * Setup AI button event listeners
 */
function setupAIButtons() {
    // AI Hint buttons
    document.querySelectorAll('.ai-hint-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const exerciseId = this.dataset.exerciseId;
            const title = this.dataset.exerciseTitle;
            const description = this.dataset.exerciseDescription;
            const language = this.dataset.language;
            getAIHint(exerciseId, title, description, language);
        });
    });
    
    // AI Improve buttons
    document.querySelectorAll('.ai-improve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const exerciseId = this.dataset.exerciseId;
            const description = this.dataset.exerciseDescription;
            const language = this.dataset.language;
            improveCode(exerciseId, description, language);
        });
    });
}

/**
 * Run code for an exercise (browser-based or AI validation)
 */
async function runCode(exerciseId, language) {
    const editor = editors[exerciseId];
    if (!editor) {
        showError(exerciseId, 'Editor not initialized');
        return;
    }

    const code = editor.getValue();
    const resultsDiv = document.getElementById('results-' + exerciseId);

    // Check execution mode
    const modeRadio = document.querySelector(`input[name="exec-mode-${exerciseId}"]:checked`);
    const executionMode = modeRadio ? modeRadio.value : 'browser';

    // Show loading
    resultsDiv.classList.remove('hidden');
    let loadingMessage = 'Running your code...';

    if (executionMode === 'ai') {
        loadingMessage = '🤖 AI is analyzing your code...';
    } else if (language === 'python' && !pyodideInstance) {
        loadingMessage = 'Loading Python environment (first time may take 10-15 seconds)...';
    }

    resultsDiv.innerHTML = `
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600 mr-3"></div>
                <span class="text-blue-800">${loadingMessage}</span>
            </div>
        </div>
    `;

    try {
        if (executionMode === 'ai') {
            // AI validation mode
            const result = await validateWithAI(code, language, exerciseId);
            displayAIResults(exerciseId, result);
        } else {
            // Browser execution mode
            const result = await executeCodeClient(code, language, exerciseId);
            displayResults(exerciseId, result, language);
        }
    } catch (error) {
        showError(exerciseId, 'Execution error: ' + error.message);
    }
}

// Global Pyodide instance
let pyodideInstance = null;
let pyodideLoading = false;

/**
 * Initialize Pyodide (Python in browser)
 */
async function initPyodide() {
    if (pyodideInstance) {
        return pyodideInstance;
    }

    if (pyodideLoading) {
        // Wait for existing load to complete
        while (pyodideLoading) {
            await new Promise(resolve => setTimeout(resolve, 100));
        }
        return pyodideInstance;
    }

    try {
        pyodideLoading = true;
        // loadPyodide is provided by the Pyodide CDN script
        pyodideInstance = await loadPyodide();
        console.log('Pyodide loaded successfully');
        return pyodideInstance;
    } catch (error) {
        console.error('Failed to load Pyodide:', error);
        throw error;
    } finally {
        pyodideLoading = false;
    }
}

/**
 * Execute code in browser (client-side)
 */
async function executeCodeClient(code, language, exerciseId) {
    const startTime = performance.now();
    let output = '';
    let errors = '';
    let testResults = [];

    // Get test cases from the page
    const testCases = getTestCases(exerciseId);

    try {
        if (language === 'javascript') {
            // Execute JavaScript in browser
            const result = executeJavaScriptClient(code, testCases);
            output = result.output;
            errors = result.errors;
            testResults = result.testResults;
        } else if (language === 'python') {
            // Execute Python in browser using Pyodide
            const result = await executePythonClient(code, testCases);
            output = result.output;
            errors = result.errors;
            testResults = result.testResults;
        } else if (language === 'cpp') {
            // For C++, show message
            errors = 'C++ execution in browser is not yet supported. Coming soon!';
        }
    } catch (error) {
        errors = error.toString();
    }

    const executionTime = ((performance.now() - startTime) / 1000).toFixed(3);

    return {
        success: !errors,
        output: output,
        errors: errors,
        test_results: testResults,
        execution_time: executionTime
    };
}

/**
 * Execute JavaScript code in browser
 */
function executeJavaScriptClient(code, testCases) {
    let output = '';
    let errors = '';
    let testResults = [];

    // Capture console.log
    const originalLog = console.log;
    const logs = [];
    console.log = function (...args) {
        logs.push(args.map(arg => String(arg)).join(' '));
        originalLog.apply(console, args);
    };

    try {
        if (testCases.length > 0) {
            // Run with test cases
            testCases.forEach(testCase => {
                logs.length = 0; // Clear logs

                try {
                    // Create a function wrapper to execute code
                    const func = new Function(code + '\n//# sourceURL=user-code.js');
                    func();

                    const actual = logs.join('\n').trim();
                    const expected = testCase.expected.trim();

                    testResults.push({
                        passed: actual === expected,
                        input: testCase.input || '',
                        expected: expected,
                        actual: actual
                    });
                } catch (err) {
                    testResults.push({
                        passed: false,
                        input: testCase.input || '',
                        expected: testCase.expected,
                        actual: 'Error: ' + err.message
                    });
                }
            });
        } else {
            // Just run the code
            const func = new Function(code + '\n//# sourceURL=user-code.js');
            func();
            output = logs.join('\n');
        }
    } catch (error) {
        errors = error.toString();
    } finally {
        console.log = originalLog;
    }

    return { output, errors, testResults };
}

/**
 * Execute Python code in browser using Pyodide
 */
async function executePythonClient(code, testCases) {
    let output = '';
    let errors = '';
    let testResults = [];

    try {
        // Load Pyodide if not already loaded
        if (!pyodideInstance) {
            pyodideInstance = await initPyodide();
        }

        if (testCases.length > 0) {
            // Run with test cases - use simpler approach
            for (const testCase of testCases) {
                try {
                    // Create a wrapper that captures print output
                    const wrappedCode = `
import sys
from io import StringIO

_output_buffer = StringIO()
_original_stdout = sys.stdout
sys.stdout = _output_buffer

try:
${code.split('\n').map(line => '    ' + line).join('\n')}
finally:
    sys.stdout = _original_stdout
    _result = _output_buffer.getvalue().strip()
`;
                    
                    await pyodideInstance.runPythonAsync(wrappedCode);
                    const actual = await pyodideInstance.runPythonAsync('_result');
                    const expected = testCase.expected.trim();

                    testResults.push({
                        passed: actual === expected,
                        input: testCase.input || '',
                        expected: expected,
                        actual: actual
                    });
                } catch (err) {
                    testResults.push({
                        passed: false,
                        input: testCase.input || '',
                        expected: testCase.expected,
                        actual: 'Error: ' + err.message
                    });
                }
            }
        } else {
            // Just run the code and capture output
            const wrappedCode = `
import sys
from io import StringIO

_output_buffer = StringIO()
_original_stdout = sys.stdout
sys.stdout = _output_buffer

try:
${code.split('\n').map(line => '    ' + line).join('\n')}
finally:
    sys.stdout = _original_stdout
    _result = _output_buffer.getvalue()
`;
            
            await pyodideInstance.runPythonAsync(wrappedCode);
            output = await pyodideInstance.runPythonAsync('_result');
        }
    } catch (error) {
        errors = error.toString();
    }

    return { output, errors, testResults };
}

/**
 * Get test cases from the page
 */
function getTestCases(exerciseId) {
    const testCases = [];
    const exerciseDiv = document.getElementById('exercise-' + exerciseId);

    if (exerciseDiv) {
        const testCaseElements = exerciseDiv.querySelectorAll('[data-test-input]');
        testCaseElements.forEach(el => {
            testCases.push({
                input: el.dataset.testInput || '',
                expected: el.dataset.testExpected || ''
            });
        });
    }

    return testCases;
}

/**
 * Display execution results
 */
function displayResults(exerciseId, result, _language) {
    const resultsDiv = document.getElementById('results-' + exerciseId);

    let html = '';

    // Test results
    if (result.test_results && result.test_results.length > 0) {
        const allPassed = result.test_results.every(test => test.passed);

        html += `
            <div class="bg-${allPassed ? 'green' : 'red'}-50 border border-${allPassed ? 'green' : 'red'}-200 rounded-lg p-4 mb-4">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-${allPassed ? 'green' : 'red'}-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${allPassed ?
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' :
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
            }
                    </svg>
                    <span class="font-semibold text-${allPassed ? 'green' : 'red'}-800">
                        ${allPassed ? 'All tests passed! 🎉' : 'Some tests failed'}
                    </span>
                </div>
                <div class="space-y-2 mt-3">
        `;

        result.test_results.forEach((test, index) => {
            html += `
                <div class="bg-white rounded p-3 text-sm">
                    <div class="flex items-center mb-1">
                        <span class="font-medium">Test ${index + 1}:</span>
                        <span class="ml-2 px-2 py-1 rounded text-xs ${test.passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                            ${test.passed ? '✓ Passed' : '✗ Failed'}
                        </span>
                    </div>
                    ${test.input ? `<div class="text-gray-600">Input: <code class="bg-gray-100 px-2 py-1 rounded">${escapeHtml(test.input)}</code></div>` : ''}
                    <div class="text-gray-600">Expected: <code class="bg-gray-100 px-2 py-1 rounded">${escapeHtml(test.expected)}</code></div>
                    <div class="text-gray-600">Got: <code class="bg-gray-100 px-2 py-1 rounded">${escapeHtml(test.actual)}</code></div>
                </div>
            `;
        });

        html += `
                </div>
            </div>
        `;

        // If all passed, show success popup and mark as complete
        if (allPassed) {
            showSuccessPopup();
            markExerciseComplete(exerciseId);
        }
    }

    // Output
    if (result.output) {
        html += `
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-semibold text-green-900">Code executed successfully!</span>
                </div>
                <div class="font-semibold text-gray-900 mb-2">Output:</div>
                <pre class="text-sm text-gray-800 whitespace-pre-wrap font-mono">${escapeHtml(result.output)}</pre>
            </div>
        `;

        // Show success popup for successful output (when no test cases)
        if (!result.errors && result.test_results.length === 0) {
            showSuccessPopup();
            markExerciseComplete(exerciseId);
        }
    }

    // Errors
    if (result.errors) {
        html += `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="font-semibold text-red-900">Errors:</div>
                    <button onclick="explainErrorWithAI('${exerciseId}', ${JSON.stringify(result.errors)}, editors['${exerciseId}'].getValue(), '${result.language || 'python'}')" 
                            class="text-sm text-purple-600 hover:text-purple-800 flex items-center gap-1">
                        🤖 Explain Error
                    </button>
                </div>
                <pre class="text-sm text-red-800 whitespace-pre-wrap font-mono">${escapeHtml(result.errors)}</pre>
            </div>
        `;
    }

    // Execution time
    if (result.execution_time) {
        html += `
            <div class="text-sm text-gray-600">
                Execution time: ${result.execution_time}s
            </div>
        `;
    }

    resultsDiv.innerHTML = html;
}

/**
 * Show error message
 */
function showError(exerciseId, message) {
    const resultsDiv = document.getElementById('results-' + exerciseId);
    resultsDiv.classList.remove('hidden');
    resultsDiv.innerHTML = `
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-red-800">${escapeHtml(message)}</span>
            </div>
        </div>
    `;
}

/**
 * Mark exercise as complete
 */
async function markExerciseComplete(exerciseId) {
    try {
        await fetch('/api/progress.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'complete_exercise',
                exercise_id: exerciseId
            })
        });

        // Reload page to update completion status
        setTimeout(() => {
            location.reload();
        }, 2000);
    } catch (error) {
        console.error('Failed to mark exercise as complete:', error);
    }
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Get AI hint for exercise
 */
async function getAIHint(exerciseId, title, description, language) {
    const editor = editors[exerciseId];
    if (!editor) return;

    const code = editor.getValue();
    const responseDiv = document.getElementById('ai-response-' + exerciseId);

    // Show loading
    responseDiv.classList.remove('hidden');
    responseDiv.innerHTML = `
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-purple-600 mr-3"></div>
                <span class="text-purple-800">🤖 AI is thinking...</span>
            </div>
        </div>
    `;

    try {
        const response = await fetch('/api/ai-helper.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'get_hint',
                exercise_title: title,
                exercise_description: description,
                user_code: code,
                language: language
            })
        });

        const result = await response.json();

        if (result.success) {
            responseDiv.innerHTML = `
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="text-2xl mr-3">🤖</span>
                        <div class="flex-1">
                            <h4 class="font-semibold text-purple-900 mb-2">AI Hint</h4>
                            <p class="text-purple-800 text-sm whitespace-pre-wrap">${escapeHtml(result.hint)}</p>
                        </div>
                        <button onclick="document.getElementById('ai-response-${exerciseId}').classList.add('hidden')" 
                                class="text-purple-600 hover:text-purple-800">✕</button>
                    </div>
                </div>
            `;
        } else {
            showAIError(responseDiv, result.message || 'Failed to get AI hint');
        }
    } catch (error) {
        showAIError(responseDiv, 'Network error: ' + error.message);
    }
}

/**
 * Get AI code improvement suggestions
 */
async function improveCode(exerciseId, description, language) {
    const editor = editors[exerciseId];
    if (!editor) return;

    const code = editor.getValue();
    if (!code.trim()) {
        alert('Please write some code first!');
        return;
    }

    const responseDiv = document.getElementById('ai-response-' + exerciseId);

    // Show loading
    responseDiv.classList.remove('hidden');
    responseDiv.innerHTML = `
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-green-600 mr-3"></div>
                <span class="text-green-800">✨ AI is analyzing your code...</span>
            </div>
        </div>
    `;

    try {
        const response = await fetch('/api/ai-helper.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'suggest_improvement',
                user_code: code,
                language: language,
                exercise_description: description
            })
        });

        const result = await response.json();

        if (result.success) {
            responseDiv.innerHTML = `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="text-2xl mr-3">✨</span>
                        <div class="flex-1">
                            <h4 class="font-semibold text-green-900 mb-2">AI Suggestions</h4>
                            <p class="text-green-800 text-sm whitespace-pre-wrap">${escapeHtml(result.suggestions)}</p>
                        </div>
                        <button onclick="document.getElementById('ai-response-${exerciseId}').classList.add('hidden')" 
                                class="text-green-600 hover:text-green-800">✕</button>
                    </div>
                </div>
            `;
        } else {
            showAIError(responseDiv, result.message || 'Failed to get suggestions');
        }
    } catch (error) {
        showAIError(responseDiv, 'Network error: ' + error.message);
    }
}

/**
 * Explain error using AI
 */
async function explainErrorWithAI(exerciseId, error, code, language) {
    const responseDiv = document.getElementById('ai-response-' + exerciseId);

    responseDiv.classList.remove('hidden');
    responseDiv.innerHTML = `
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-yellow-600 mr-3"></div>
                <span class="text-yellow-800">🤖 AI is explaining the error...</span>
            </div>
        </div>
    `;

    try {
        const response = await fetch('/api/ai-helper.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'explain_error',
                error: error,
                user_code: code,
                language: language
            })
        });

        const result = await response.json();

        if (result.success) {
            responseDiv.innerHTML = `
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <span class="text-2xl mr-3">🤖</span>
                        <div class="flex-1">
                            <h4 class="font-semibold text-yellow-900 mb-2">AI Error Explanation</h4>
                            <p class="text-yellow-800 text-sm whitespace-pre-wrap">${escapeHtml(result.explanation)}</p>
                        </div>
                        <button onclick="document.getElementById('ai-response-${exerciseId}').classList.add('hidden')" 
                                class="text-yellow-600 hover:text-yellow-800">✕</button>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        // Silently fail for error explanation
        console.error('AI error explanation failed:', error);
    }
}

/**
 * Show AI error message
 */
function showAIError(responseDiv, message) {
    responseDiv.innerHTML = `
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-red-800 text-sm">${escapeHtml(message)}</span>
            </div>
        </div>
    `;
}

/**
 * Show success popup when all tests pass
 */
function showSuccessPopup() {
    // Create popup element
    const popup = document.createElement('div');
    popup.id = 'success-popup';
    popup.className = 'fixed inset-0 flex items-center justify-center z-50 animate-fade-in';
    popup.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';

    popup.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md mx-4 transform animate-bounce-in">
            <div class="text-center">
                <div class="text-6xl mb-4">🎉</div>
                <h2 class="text-3xl font-bold text-green-600 mb-2">Success!</h2>
                <p class="text-gray-700 text-lg mb-6">All tests passed! Great job!</p>
                <div class="flex gap-3 justify-center">
                    <button onclick="closeSuccessPopup()" 
                            class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                        Continue Learning
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(popup);

    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes bounce-in {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
        .animate-bounce-in {
            animation: bounce-in 0.5s ease-out;
        }
    `;
    document.head.appendChild(style);

    // Auto close after 3 seconds
    setTimeout(() => {
        closeSuccessPopup();
    }, 3000);
}

/**
 * Close success popup
 */
function closeSuccessPopup() {
    const popup = document.getElementById('success-popup');
    if (popup) {
        popup.style.opacity = '0';
        popup.style.transition = 'opacity 0.3s';
        setTimeout(() => {
            popup.remove();
        }, 300);
    }
}


/**
 * Validate code with AI
 */
async function validateWithAI(code, language, exerciseId) {
    // Get exercise description and expected output from page
    const exerciseDiv = document.getElementById('exercise-' + exerciseId);
    const description = exerciseDiv ? exerciseDiv.querySelector('p')?.textContent || '' : '';

    // Get expected output from test cases
    const testCases = getTestCases(exerciseId);
    const expectedOutput = testCases.length > 0 ? testCases[0].expected : 'Check if code works correctly';

    const response = await fetch('/api/ai-helper.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'validate_code',
            user_code: code,
            language: language,
            exercise_description: description,
            expected_output: expectedOutput
        })
    });

    return await response.json();
}

/**
 * Display AI validation results
 */
function displayAIResults(exerciseId, result) {
    const resultsDiv = document.getElementById('results-' + exerciseId);

    if (!result.success) {
        showError(exerciseId, result.message || 'AI validation failed');
        return;
    }

    const passed = result.passed;
    const bgColor = passed ? 'green' : 'red';

    let html = `
        <div class="bg-${bgColor}-50 border border-${bgColor}-200 rounded-lg p-4 mb-4">
            <div class="flex items-center mb-3">
                <svg class="w-6 h-6 text-${bgColor}-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${passed ?
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' :
            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
        }
                </svg>
                <span class="font-semibold text-${bgColor}-900 text-lg">
                    ${passed ? '🎉 AI Validation: PASSED!' : '❌ AI Validation: FAILED'}
                </span>
            </div>
            
            <div class="space-y-3">
                <div class="bg-white rounded-lg p-3">
                    <div class="font-semibold text-gray-900 mb-1">AI Analysis:</div>
                    <p class="text-gray-700 text-sm">${escapeHtml(result.reason)}</p>
                </div>
                
                <div class="bg-white rounded-lg p-3">
                    <div class="font-semibold text-gray-900 mb-1">Expected Output:</div>
                    <pre class="text-sm text-gray-800 font-mono">${escapeHtml(result.output)}</pre>
                </div>
            </div>
        </div>
    `;

    resultsDiv.innerHTML = html;

    // If passed, show success popup and mark complete
    if (passed) {
        showSuccessPopup();
        markExerciseComplete(exerciseId);
    }
}
