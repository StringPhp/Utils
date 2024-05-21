<?php

namespace StringPhp\Utils;

use Random\RandomException;

/**
 * Get all the classes in a given directory
 *
 * @param bool $recursive Loop into subdirectories or not
 * @param string|null $namespace The namespace to prepend to the class name, nested directories will be appended to the namespace (e.g. "CommandString\VuePhp\\Controllers")
 * @param array $ignoreDirectories List of absolute paths to ignore
 *
 * @return array<string[]> [$className, $namespace, $fullClassName, $directory]
 */
function getClasses(string $directory, ?string $namespace = null, bool $recursive = true, array $ignoreDirectories = []): array
{
    $classes = [];

    ($getClasses = static function (string $directory) use ($ignoreDirectories, $recursive, &$namespace, &$getClasses, &$classes) {
        foreach (scandir($directory) as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $path = realpath("{$directory}/{$file}");

            if (in_array($path, $ignoreDirectories)) {
                continue;
            }

            if (is_dir($path) && $recursive) {
                $oldNamespace = $namespace;
                $namespace = "{$namespace}\\{$file}";
                $getClasses($path);
                $namespace = $oldNamespace;
            }

            if (
                !str_ends_with($file, '.php') ||
                !is_file($path)
            ) {
                continue;
            }

            $relativePath = str_replace(realpath($directory), '', realpath($file));
            $relativeDirectory = substr(dirname($relativePath), 1);
            $className = basename($file, '.php');

            if (!empty($relativeDirectory)) {
                $fullClassName = "{$namespace}\\{$relativeDirectory}\\{$className}";
            } else {
                $fullClassName = "{$namespace}\\{$className}";
            }

            if (!class_exists($fullClassName)) {
                continue;
            }

            $classes[] = [$className, $namespace, $fullClassName, $directory];
        }
    })($directory);

    return $classes;
}

/**
 * Converts a snake_case string to camelCase
 *
 * @param string $string
 * @return string
 */
function snakeToCamelCase(string $string): string
{
    return str_replace('_', '', ucwords($string, '_'));
}

/**
 * Converts a camelCase string to snake_case
 *
 * @param string $string
 * @return string
 */
function camelToSnakeCase(string $string): string
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
}

/**
 * Generates a psuedo secure UUID
 *
 * @param int $length The length of the UUID
 * @throws RandomException If a source of secure random data cannot be found
 */
function generateUuid(int $length = 16): string
{
    $data = random_bytes($length / 2);

    return bin2hex($data);
}
