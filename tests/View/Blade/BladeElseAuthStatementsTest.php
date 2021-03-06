<?php

namespace Illuminate\Tests\View\Blade;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\View\Compilers\BladeCompiler;

class BladeElseAuthStatementsTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testElseAuthStatementsAreCompiled()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__);
        $string = '@auth("api")
breeze
@elseauth("standard")
wheeze
@endauth';
        $expected = '<?php if(auth()->guard("api")->check()): ?>
breeze
<?php elseif(auth()->guard("standard")->check()): ?>
wheeze
<?php endif; ?>';
        $this->assertEquals($expected, $compiler->compileString($string));
    }

    public function testPlainElseAuthStatementsAreCompiled()
    {
        $compiler = new BladeCompiler($this->getFiles(), __DIR__);
        $string = '@auth("api")
breeze
@elseauth
wheeze
@endauth';
        $expected = '<?php if(auth()->guard("api")->check()): ?>
breeze
<?php elseif(auth()->guard()->check()): ?>
wheeze
<?php endif; ?>';
        $this->assertEquals($expected, $compiler->compileString($string));
    }

    protected function getFiles()
    {
        return m::mock('Illuminate\Filesystem\Filesystem');
    }
}
