<?php

namespace Taecontrol\Larvis\ValueObjects\Data;

use Symfony\Component\HttpKernel\Kernel;
use Illuminate\Contracts\Support\Arrayable;
use Taecontrol\Larvis\Exceptions\MissingAppNameException;

class AppData implements Arrayable
{
    public function __construct(
        public readonly string|null $framework,
        public readonly string|null $frameworkVersion,
        public readonly string $name,
        public readonly string $directory,
        public readonly string|null $language,
        public readonly string|null $languageVersion,
    ) {
    }

    public static function generate(): AppData
    {
        $name = config('app.name');

        if (! $name || $name === '') {
            throw new MissingAppNameException();
        }

        $framework = self::getFramework();

        return new AppData(
            framework: $framework['name'],
            directory: base_path(),
            frameworkVersion: $framework['version'],
            name: $name,
            language: 'PHP',
            languageVersion: PHP_VERSION
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'directory' => $this->directory,
            'framework' => $this->framework,
            'framework_version' => $this->frameworkVersion,
            'language' => $this->language,
            'language_version' => $this->languageVersion,
        ];
    }

    public static function getFramework(): array
    {
        $framework = null;
        $version = null;

        $composerFileInJson = file_get_contents(base_path('composer.json'));

        if (! $composerFileInJson) {
            return [
                'name' => $framework,
                'version' => $version,
            ];
        }

        $composerFile = json_decode($composerFileInJson, true);

        if ($composerFile) {
            if (key_exists('laravel/framework', $composerFile['require'])) {
                $framework = 'Laravel';
                $version = app()->version();
            }

            if (key_exists('symfony/framework-bundle', $composerFile['require'])) {
                $framework = 'Symphony';
                $version = Kernel::VERSION;
            }
        }

        return [
            'name' => $framework,
            'version' => $version,
        ];
    }

    public static function fromArray(array $args): AppData
    {
        return new AppData(
            name: $args['name'],
            directory: $args['directory'],
            framework: $args['framework'],
            frameworkVersion: $args['framework_version'],
            language: $args['language'],
            languageVersion: $args['language_version'],
        );
    }
}
