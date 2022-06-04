<?php

function shutdown(): void {
    echo "Bye!\n";
    exit(0);
}

$userName = getenv("USER");

if (!$userName) {
    $userName = "user";
}

echo "Hello, $userName!\n";
echo "Here's the list of options, type an appropriate digit (in braces) to proceed with the action\n";

$invalidInput = false;

do {
    echo "Start shopping (1)\n";
    echo "Profile settings (2)\n";
    echo "Get the final score (3)\n";
    echo "Exit the program (0 or Ctrl-D)\n";

    $option = readline("> ");

    if (is_bool($option) && !$option) { // readline() return "false" if end-of-input occurred (triggered by Ctrl-D)
        shutdown();
    }

    switch ($option) {
        case "0":
            shutdown();
            break;
        case "1":
        case "2":
        case "3":
            $invalidInput = false;
            break; // TODO
        default:
            echo "Unknown entry '$option', please enter a valid one in range [0-3]\n";
            $invalidInput = true;
            break;
    }
} while ($invalidInput);
