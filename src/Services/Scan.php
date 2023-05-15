<?php

namespace io3x1\FilamentTranslations\Services;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class Scan
{
    /**
     * The Filesystem instance.
     *
     * @var Filesystem
     */
    private $disk;

    /**
     * The paths to directories where we look for localised strings to scan.
     *
     * @var array
     */
    private $scannedPaths;

    /**
     * Regex patterns to find translations.
     */
    protected $patternA;
    protected $patternB;
    protected $patternC;

    /**
     * Manager constructor.
     *
     * @param Filesystem $disk
     */
    public function __construct(Filesystem $disk)
    {
        $this->disk = $disk;
        $this->scannedPaths = collect([]);
    }

    /**
     * @param $path
     */
    public function addScannedPath($path): void
    {
        $this->scannedPaths->push($path);
    }

    public function getAllViewFilesWithTranslations(): array
    {
        /*
         * This pattern is derived from Barryvdh\TranslationManager by Barry vd. Heuvel <barryvdh@gmail.com>
         *
         * https://github.com/barryvdh/laravel-translation-manager/blob/master/src/Manager.php
         */
        $functions = [
            'trans',
            'trans_choice',
            'Lang::get',
            'Lang::choice',
            'Lang::trans',
            'Lang::transChoice',
            '@lang',
            '@choice'
        ];

        $this->patternA =
            // See https://regex101.com/r/jS5fX0/4
            '[^\w]' . // Must not start with any alphanum or _
            '(?<!->)' . // Must not start with ->
            '(' . implode('|', $functions) . ')' . // Must start with one of the functions
            "\(" . // Match opening parentheses
            "[\'\"]" . // Match " or '
            '(' . // Start a new group to match:
            '([a-zA-Z0-9_\/-]+::)?' .
            '[a-zA-Z0-9_-]+' . // Must start with group
            "([.][^\1)$]+)+" . // Be followed by one or more items/keys
            ')' . // Close group
            "[\'\"]" . // Closing quote
            "[\),]"  // Close parentheses or new parameter
        ;

        $this->patternB =
            // See https://regex101.com/r/2EfItR/2
            '[^\w]' . // Must not start with any alphanum or _
            '(?<!->)' . // Must not start with ->
            '(__|Lang::getFromJson)' . // Must start with one of the functions
            '\(' . // Match opening parentheses

            '[\"]' . // Match "
            '(' . // Start a new group to match:
            '[^"]+' . //Can have everything except "
            //            '(?:[^"]|\\")+' . //Can have everything except " or can have escaped " like \", however it is not working as expected
            ')' . // Close group
            '[\"]' . // Closing quote

            '[\)]'  // Close parentheses or new parameter
        ;

        $this->patternC =
            // See https://regex101.com/r/VaPQ7A/2
            '[^\w]' . // Must not start with any alphanum or _
            '(?<!->)' . // Must not start with ->
            '(__|Lang::getFromJson)' . // Must start with one of the functions
            '\(' . // Match opening parentheses

            '[\']' . // Match '
            '(' . // Start a new group to match:
            "[^']+" . //Can have everything except '
            //            "(?:[^']|\\')+" . //Can have everything except 'or can have escaped ' like \', however it is not working as expected
            ')' . // Close group
            '[\']' . // Closing quote

            '[\)]'  // Close parentheses or new parameter
        ;

        $trans = collect();
        $__ = collect();
        $excludedPaths = config('filament-translations.excludedPaths');

        // FIXME maybe we can count how many times one translation is used and eventually display it to the user

        /** @var SplFileInfo $file */
        foreach ($this->scannedPaths->toArray() as $path) {
            $this->scanFilesRecursively($path, $trans, $__, $excludedPaths);
        }

        return [$trans->flatten()->unique(), $__->flatten()->unique()];
    }

    protected function scanFilesRecursively($directory, $trans, $__, $excludedPaths)
    {
        $files = $this->disk->files($directory);
        $directories = $this->disk->directories($directory);

        foreach ($files as $file) {
            $dir = dirname($file);

            if ($this->startsWithExcludedPath($dir, $excludedPaths)) {
                continue;
            }

            if (preg_match_all("/$this->patternA/siU", $this->disk->get($file), $matches)) {
                $trans->push($matches[2]);
            }

            if (preg_match_all("/$this->patternB/siU", $this->disk->get($file), $matches)) {
                $__->push($matches[2]);
            }

            if (preg_match_all("/$this->patternC/siU", $this->disk->get($file), $matches)) {
                $__->push($matches[2]);
            }
        }

        foreach ($directories as $subdirectory) {
            $this->scanFilesRecursively($subdirectory, $trans, $__, $excludedPaths);
        }
    }

    protected function startsWithExcludedPath($path, $excludedPaths)
    {
        foreach ($excludedPaths as $excludedPath) {
            if (strpos($path, $excludedPath) === 0) {
                return true;
            }
        }

        return false;
    }
}
