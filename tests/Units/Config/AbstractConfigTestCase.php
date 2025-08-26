<?php declare(strict_types=1);

namespace Tests\Units\Config;

use Hinasila\DiContainer\DiContainer;
use Hinasila\DiContainer\DiRuleList;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class AbstractConfigTestCase extends TestCase
{
    /**
     * @var DiRuleList
     */
    protected $rlist;

    /**
     * @var DiContainer
     */
    protected $dic;

    /**
     * @var string[]
     */
    protected $files = [];

    protected function setUp(): void
    {
        $classname   = (new ReflectionClass(static::class))->getShortName();
        $filename    = \str_replace('Test', '', $classname);
        $this->files = \array_merge($this->files, [$filename . '.php']);
        foreach ($this->files as $file) {
            $path = \DATA_DIR . '/' . $file;
            if (\file_exists($path) === true) {
                require_once $path;
            }
        }

        $configPath = \DATA_DIR . '/config/' . $filename . '.json';
        $json       = [];

        if (\file_exists($configPath) === true) {
            $json = \json_decode((string) \file_get_contents($configPath), true);
        }

        $rlist       = new DiRuleList();
        $this->rlist = $rlist->addRules($json);
        $this->dic   = new DiContainer($this->rlist);
    }
}
