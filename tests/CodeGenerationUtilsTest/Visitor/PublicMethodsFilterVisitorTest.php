<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

namespace CodeGenerationUtilsTest\Visitor;

use CodeGenerationUtils\Visitor\PublicMethodsFilterVisitor;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PHPUnit_Framework_TestCase;

/**
 * Tests for {@see \CodeGenerationUtils\Visitor\ClassClonerVisitor}
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 * @license MIT
 *
 * @covers \CodeGenerationUtils\Visitor\PublicMethodsFilterVisitor
 */
class PublicMethodsFilterVisitorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider nodeProvider
     *
     * @param PhpParser\Node $node
     * @param mixed          $expected
     */
    public function testRemovesOnlyPrivateMethods(Node $node, $expected)
    {
        $visitor = new PublicMethodsFilterVisitor();

        $this->assertSame($expected, $visitor->leaveNode($node));
    }

    public function nodeProvider()
    {
        return array(
            array(
                new ClassMethod(
                    'foo',
                    array('type' => Class_::MODIFIER_PUBLIC)
                ),
                null,
            ),
            array(
                new ClassMethod(
                    'foo',
                    array('type' => Class_::MODIFIER_PROTECTED)
                ),
                false,
            ),
            array(
                new ClassMethod(
                    'foo',
                    array('type' => Class_::MODIFIER_PRIVATE)
                ),
                false,
            ),
            array(new Class_('foo'), null,),
        );
    }
}
