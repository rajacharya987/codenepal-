-- CodeNepal Sample Data
-- Run this after schema.sql

USE codenepal;

-- Insert admin user (password: admin123)
INSERT INTO users (id, email, password_hash, name, role) VALUES
('admin-001', 'admin@codenepal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin');

-- Insert sample regular user (password: user123)
INSERT INTO users (id, email, password_hash, name, role) VALUES
('user-001', 'user@codenepal.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test User', 'user');

-- Insert Python Beginner Course
INSERT INTO courses (id, title, description, language, category, duration, is_published, is_free) VALUES
('python-basics', 'Python Basics', 'Learn the fundamentals of Python programming from scratch. Perfect for beginners with no prior coding experience.', 'python', 'beginner', '4 weeks', TRUE, TRUE);

-- Insert JavaScript Beginner Course
INSERT INTO courses (id, title, description, language, category, duration, is_published, is_free) VALUES
('js-fundamentals', 'JavaScript Fundamentals', 'Master the basics of JavaScript, the language of the web. Build interactive websites and web applications.', 'javascript', 'beginner', '5 weeks', TRUE, TRUE);

-- Insert C++ Intermediate Course
INSERT INTO courses (id, title, description, language, category, duration, is_published, is_free) VALUES
('cpp-intermediate', 'C++ Programming', 'Dive into C++ programming with object-oriented concepts, memory management, and data structures.', 'cpp', 'intermediate', '6 weeks', TRUE, TRUE);

-- Python Basics - Lesson 1
INSERT INTO lessons (id, course_id, title, content, order_index, is_locked) VALUES
('py-lesson-1', 'python-basics', 'Introduction to Python', 
'# Welcome to Python Programming!

Python is a powerful, easy-to-learn programming language. It has efficient high-level data structures and a simple but effective approach to object-oriented programming.

## Why Learn Python?

- **Easy to Learn**: Python has a simple syntax that mimics natural language
- **Versatile**: Used in web development, data science, AI, automation, and more
- **Popular**: One of the most in-demand programming languages
- **Great Community**: Tons of resources and libraries available

## Your First Python Program

Let''s start with the classic "Hello, World!" program:

```python
print("Hello, World!")
```

The `print()` function displays text on the screen. Try it in the exercise below!', 
1, FALSE);

-- Python Basics - Lesson 2
INSERT INTO lessons (id, course_id, title, content, order_index, is_locked) VALUES
('py-lesson-2', 'python-basics', 'Variables and Data Types', 
'# Variables and Data Types

Variables are containers for storing data values. In Python, you don''t need to declare the type of a variable.

## Creating Variables

```python
name = "Alice"
age = 25
height = 5.6
is_student = True
```

## Data Types

- **String**: Text data (`"Hello"`)
- **Integer**: Whole numbers (`42`)
- **Float**: Decimal numbers (`3.14`)
- **Boolean**: True or False values

## Using Variables

```python
greeting = "Hello"
name = "Bob"
message = greeting + ", " + name + "!"
print(message)  # Output: Hello, Bob!
```', 
2, TRUE);

-- JavaScript Fundamentals - Lesson 1
INSERT INTO lessons (id, course_id, title, content, order_index, is_locked) VALUES
('js-lesson-1', 'js-fundamentals', 'Introduction to JavaScript', 
'# Welcome to JavaScript!

JavaScript is the programming language of the web. It allows you to create interactive and dynamic websites.

## What Can JavaScript Do?

- Make websites interactive
- Create web applications
- Build mobile apps
- Develop server-side applications with Node.js

## Your First JavaScript Program

```javascript
console.log("Hello, World!");
```

The `console.log()` function prints messages to the browser console. Let''s try it!', 
1, FALSE);

-- C++ Programming - Lesson 1
INSERT INTO lessons (id, course_id, title, content, order_index, is_locked) VALUES
('cpp-lesson-1', 'cpp-intermediate', 'C++ Basics Review', 
'# C++ Programming Basics

C++ is a powerful general-purpose programming language. It supports object-oriented, procedural, and generic programming.

## Basic Structure

```cpp
#include <iostream>
using namespace std;

int main() {
    cout << "Hello, World!" << endl;
    return 0;
}
```

## Key Components

- `#include <iostream>`: Includes input/output library
- `using namespace std`: Uses standard namespace
- `main()`: Entry point of the program
- `cout`: Output stream
- `endl`: End line', 
1, FALSE);

-- Exercise 1: Python Hello World
INSERT INTO exercises (id, lesson_id, title, description, starter_code, solution, difficulty, points) VALUES
('py-ex-1', 'py-lesson-1', 'Print Hello World', 
'Write a Python program that prints "Hello, World!" to the console.',
'# Write your code below\n',
'print("Hello, World!")',
'easy', 10);

-- Test cases for Python Hello World
INSERT INTO test_cases (exercise_id, input, expected_output, is_hidden, order_index) VALUES
('py-ex-1', '', 'Hello, World!', FALSE, 1);

-- Exercise 2: Python Variables
INSERT INTO exercises (id, lesson_id, title, description, starter_code, solution, difficulty, points) VALUES
('py-ex-2', 'py-lesson-2', 'Create and Print Variables', 
'Create a variable called `name` with your name, and print a greeting message.',
'# Create a variable and print a greeting\nname = \n',
'name = "Student"\nprint("Hello, " + name + "!")',
'easy', 10);

-- Test cases for Python Variables
INSERT INTO test_cases (exercise_id, input, expected_output, is_hidden, order_index) VALUES
('py-ex-2', '', 'Hello, Student!', FALSE, 1);

-- Exercise 3: JavaScript Hello World
INSERT INTO exercises (id, lesson_id, title, description, starter_code, solution, difficulty, points) VALUES
('js-ex-1', 'js-lesson-1', 'Console Log Hello World', 
'Write a JavaScript program that prints "Hello, World!" to the console.',
'// Write your code below\n',
'console.log("Hello, World!");',
'easy', 10);

-- Test cases for JavaScript Hello World
INSERT INTO test_cases (exercise_id, input, expected_output, is_hidden, order_index) VALUES
('js-ex-1', '', 'Hello, World!', FALSE, 1);

-- Exercise 4: C++ Hello World
INSERT INTO exercises (id, lesson_id, title, description, starter_code, solution, difficulty, points) VALUES
('cpp-ex-1', 'cpp-lesson-1', 'C++ Hello World', 
'Write a C++ program that prints "Hello, World!" to the console.',
'#include <iostream>\nusing namespace std;\n\nint main() {\n    // Write your code here\n    \n    return 0;\n}',
'#include <iostream>\nusing namespace std;\n\nint main() {\n    cout << "Hello, World!" << endl;\n    return 0;\n}',
'easy', 10);

-- Test cases for C++ Hello World
INSERT INTO test_cases (exercise_id, input, expected_output, is_hidden, order_index) VALUES
('cpp-ex-1', '', 'Hello, World!', FALSE, 1);

-- Add hints for exercises
INSERT INTO hints (exercise_id, hint_text, order_index) VALUES
('py-ex-1', 'Use the print() function to display text', 1),
('py-ex-1', 'Put your text inside quotes: "Hello, World!"', 2),
('py-ex-2', 'Assign a value to the name variable using the = operator', 1),
('py-ex-2', 'Use string concatenation with + to combine strings', 2),
('js-ex-1', 'Use console.log() to print to the console', 1),
('js-ex-1', 'Put your text inside quotes: "Hello, World!"', 2),
('cpp-ex-1', 'Use cout to print output', 1),
('cpp-ex-1', 'Don''t forget the << operator and endl', 2);
