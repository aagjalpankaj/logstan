<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan;

use Nette\Neon\Exception;
use Nette\Neon\Neon;

readonly class Config
{
    public function __construct(
        public int $logContextMaxKeys,
        public string $logContextCaseStyle,
        public int $logMessageMaxLength,
    ) {}

    /**
     * @throws Exception
     */
    public static function load(): self
    {
        $config = self::loadConfigArray();

        return new self(
            logContextMaxKeys: $config['parameters']['logContext']['maxKeys'] ?? 10,
            logContextCaseStyle: $config['parameters']['logContext']['caseStyle'] ?? 'snake_case',
            logMessageMaxLength: $config['parameters']['logMessage']['maxLength'] ?? 1000,
        );
    }

    /**
     * @throws Exception
     */
    private static function loadConfigArray(): array
    {
        $configPath = self::findConfigFile();

        if ($configPath === null || ! file_exists($configPath)) {
            return [];
        }

        return Neon::decodeFile($configPath);
    }

    private static function findConfigFile(): ?string
    {
        $possiblePaths = [
            getcwd().'/logstan.neon',
            getcwd().'/logstan.neon.dist',
            __DIR__.'/../logstan.neon.dist',
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
