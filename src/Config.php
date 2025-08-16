<?php

declare(strict_types=1);

namespace Aagjalpankaj\Logstan;

use Aagjalpankaj\Logstan\Enums\CaseStyle;
use Nette\Neon\Exception;
use Nette\Neon\Neon;

readonly class Config
{
    public function __construct(
        public int $logContextMaxKeys,
        public CaseStyle $logContextKeyCaseStyle,
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
            logContextKeyCaseStyle: CaseStyle::from($config['parameters']['logContext']['keyCaseStyle']) ?? CaseStyle::SNAKE_CASE,
            logMessageMaxLength: $config['parameters']['logMessage']['maxLength'] ?? 120,
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
