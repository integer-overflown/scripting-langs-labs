<?php

class Product
{
    // Shorthand construct notation, new in PHP 8.0
    public function __construct(public string $name,
                                public float  $price,
                                public int    $ageRestriction = 0)
    {
    }
}

class UserProfile
{
    public string $userName;
    public int $age;

    public function __construct(?string $userName = null, int $age = 0)
    {
        if ($userName === null) {
            // request username from USER environment variable (is there on all *nix systems)
            $this->userName = getenv("USER") ?: "unknown";
        }
        $this->age = $age;
    }
}

$products = [
    1 => new Product("Coffee", 10),
    2 => new Product("Cocoa", 7),
    3 => new Product("Tea", 5),
    4 => new Product("Cola", 14),
    5 => new Product("Pepsi", 15),
    6 => new Product("Sprite", 12),
    7 => new Product("Juice", 14)
];

$userProfile = new UserProfile();

function shutdown(): void
{
    echo "Bye!\n";
    exit(0);
}

function parseIntegerInput(string $input, int $rangeStart, $rangeEnd): ?int
{
    if (!is_numeric($input)) {
        return null;
    }
    $parsed = intval($input);
    if ($parsed < $rangeStart || $parsed > $rangeEnd) {
        return null;
    }
    return $parsed;
}

function startShoppingEntry(): void
{
    global $products;
    $numProducts = count($products);

    echo "Here's what we have:\n";
    foreach ($products as $num => $product) {
        echo "$num) $product->name\n";
    }

    echo "Choose any by typing a number of menu entry\n";

    for (; ;) {
        $input = readline("product> ");

        if ($input === false) { // end-of-input
            break;
        }

        $tokens = preg_split('/\s+/', $input, flags: PREG_SPLIT_NO_EMPTY);

        if (empty($tokens)) {
            echo "Please enter a number in range [1, $numProducts]\n";
            continue;
        }

        $productIndex = parseIntegerInput($tokens[0], 0, $numProducts); // '0' is allowed as exit request
        $productCount = 1;

        if ($productIndex === null) {
            echo "Please enter a valid product index in range [1, $numProducts] (cannot recognize '" . $tokens[0] . "')\n";
            continue;
        }

        if ($productIndex == 0) {
            break;
        }

        if (count($tokens) > 1) {
            $productCount = parseIntegerInput($tokens[1], 1, $numProducts);

            if ($productCount === null) {
                echo "Please enter a valid product count, should be an integer larger than 1 (cannot recognize '" . $tokens[1] . "')\n";
                continue;
            }
        }

        $chosenProduct = $products[$productIndex];

        echo "You've chosen '$chosenProduct->name' in amount of $productCount\n";
    }

    echo "Done shopping\n";
}

echo "Hello, $userProfile->userName!\n";
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

    $option = trim($option);

    switch ($option) {
        case "0":
            shutdown();
            break;
        case "1":
            echo "-- Start shopping --\n";
            startShoppingEntry();
            break;
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
