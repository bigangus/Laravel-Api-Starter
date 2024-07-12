<?php

if (!function_exists('scan_directory')) {
    function scan_directory(
        $directory,
        &$controllersMethods,
        $excludeMethods,
        $baseNamespace = 'App\\Http\\Controllers'
    ): void {
        $items = array_filter(scandir($directory), function ($entry) {
            return $entry !== '.' && $entry !== '..';
        });

        foreach ($items as $item) {
            $path = $directory . '/' . $item;
            if (is_dir($path)) {
                scan_directory($path, $controllersMethods, $excludeMethods, $baseNamespace);
            } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                $relativeNamespace = str_replace(realpath(app_path('Http/Controllers')), '', $directory);
                $relativeNamespace = trim(str_replace(DIRECTORY_SEPARATOR, '\\', $relativeNamespace), '\\');
                $namespace = $baseNamespace . '\\' . $relativeNamespace;
                $className = pathinfo($path, PATHINFO_FILENAME);
                $fullClassName = $namespace . '\\' . $className;

                if (!class_exists($fullClassName, false)) {
                    require_once $path;
                }

                try {
                    if (class_exists($fullClassName, false)) {
                        $reflectionClass = new ReflectionClass($fullClassName);
                        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
                        $methodNames = [];

                        foreach ($methods as $method) {
                            if ($method->class == $fullClassName && !in_array($method->name, $excludeMethods)) {
                                $methodNames[] = $method->name;
                            }
                        }

                        $prefix = str_replace('\\', '/', $relativeNamespace);
                        $prefix = Str::lower(implode('/', array_map('ucfirst', explode('/', $prefix))));
                        $controllersMethods[$prefix] = ['className' => $fullClassName, 'methods' => $methodNames];
                    }
                } catch (ReflectionException $e) {
                    error_log('ReflectionException: ' . $e->getMessage());
                }
            }
        }
    }
}
