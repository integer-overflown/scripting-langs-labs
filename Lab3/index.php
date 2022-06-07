<?php
echo readfile($_SERVER['DOCUMENT_ROOT'] . '/index.html') or die("not found: index.html");
