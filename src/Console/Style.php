<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\Console;

use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * features:
 *
 * - accept multi line answers
 */
class Style extends SymfonyStyle
{
    /**
     * @inheritdoc
     */
    public function askQuestion(Question $question)
    {
        return parent::askQuestion((new HereDocValidation)->bind($question));
    }
}
