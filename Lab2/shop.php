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

// Current record of life duration
// If user inputs more, it's either a mistake
// or we should go grab Guinness' book to write his age down as a new record :)
const MAX_ALLOWED_AGE_INPUT = 122;
const MIN_ALLOWED_AGE_INPUT = 6;

$products = [
    1 => new Product("Coffee", 10),
    2 => new Product("Cocoa", 7),
    3 => new Product("Tea", 5),
    4 => new Product("Cola", 14),
    5 => new Product("Pepsi", 15),
    6 => new Product("Sprite", 12),
    7 => new Product("Juice", 14),
    8 => new Product("Energy drink", 20, ageRestriction: 16),
    9 => new Product("Beer", 25, ageRestriction: 18),
    10 => new Product("Whiskey", 60, ageRestriction: 22)
];

$userProfile = new UserProfile();
$totalScore = 0.0;

enum Status {
    case Success;
    case Interrupted;
}

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

function startShoppingEntry(): Status
{
    global $products;
    global $userProfile;
    global $totalScore;

    $numProducts = count($products);

    echo "Here's what we have:\n";
    foreach ($products as $num => $product) {
        echo "$num) $product->name, \$$product->price per each\n";
    }

    echo "Choose any by typing a number of menu entry\n";
    echo "You may add amount of products to take, such as: '1 10' to take 10 units of 1 product\n";

    for (; ;) {
        $input = readline("product> ");

        if ($input === false) { // end-of-input
            return Status::Interrupted;
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
            $productCount = parseIntegerInput($tokens[1], 1, PHP_INT_MAX);

            if ($productCount === null) {
                echo "Please enter a valid product count, should be an integer larger than 1 (cannot recognize '" . $tokens[1] . "')\n";
                continue;
            }
        }

        $chosenProduct = $products[$productIndex];

        if ($userProfile->age < $chosenProduct->ageRestriction) {
            echo "Sorry, you cannot buy '$chosenProduct->name' yet: you must be $chosenProduct->ageRestriction+ years old, but you're only $userProfile->age\n";
            continue;
        }

        echo "You've chosen '$chosenProduct->name' in amount of $productCount\n";
        $totalScore += $chosenProduct->price * $productCount;
    }

    echo "Done shopping\n";
    return Status::Success;
}

function setupProfile(): Status
{
    global $userProfile;
    $prompt = "profile> ";

    echo "Please enter your name\n";
    for (; ;) {
        $userName = readline($prompt);

        if ($userName === false) {
            return Status::Interrupted;
        }

        // check for blank and too short input
        if (preg_match('/^\s*$/', $userName) > 0 || strlen($userName = trim($userName)) < 3) {
            echo "Please enter a valid name at least 3 characters long\n";
            continue;
        }

        break;
    }

    echo "Please enter your age\n";
    for (; ;) {
        $input = readline($prompt);

        if ($input === false) {
            return Status::Interrupted;
        }

        $age = parseIntegerInput(trim($input), MIN_ALLOWED_AGE_INPUT, MAX_ALLOWED_AGE_INPUT);

        if ($age === null) {
            echo "Please enter a valid age in range [" . MIN_ALLOWED_AGE_INPUT . ", " . MAX_ALLOWED_AGE_INPUT . "]\n";
            continue;
        }

        break;
    }

    $userProfile->userName = $userName;
    $userProfile->age = $age;

    return Status::Success;
}

echo "Hello, $userProfile->userName!\n";
echo "Let's setup profile first\n";

$status = setupProfile();

if ($status != Status::Success) {
    shutdown();
}

echo "I'll call you $userProfile->userName\n";
echo "Here's the list of options, type an appropriate digit (in braces) to proceed with the action\n";

for (; ;) {
    if ($status == Status::Interrupted) {
        shutdown();
    }

    echo "Start shopping (1)\n";
    echo "Profile settings (2)\n";
    echo "Get the final score (3)\n";
    echo "Exit the program (0 or Ctrl-D)\n";

    $option = readline("menu> ");

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
            $status = startShoppingEntry();
            echo "--\n";
            break;
        case "2":
            echo "-- Profile settings --\n";
            $status = setupProfile();
            echo "--\n";
            break;
        case "3":
            echo "-- Get the final score --\n";
            echo "Your final score is: \$$totalScore\n";
            echo "--\n";
            break;
        default:
            echo "Unknown entry '$option', please enter a valid one in range [0-3]\n";
            break;
    }
}
