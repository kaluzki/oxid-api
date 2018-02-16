<?php
/**
 * (c) kaluzki
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace kaluzki\Console;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Multi-line input for questions
 *
 * @example
 *
 * question:
 * > <<<EOT
 * here is
 * your answer
 * EOT
 */
class HereDocValidation
{
    /**
     * @var string
     */
    private $eot;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var Question
     */
    private $question;

    /**
     * @var callable
     */
    private $validator;

    /**
     * @param string $eot
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param Question $question
     */
    public function __construct(
        $eot = null,
        InputInterface $input = null,
        OutputInterface $output = null,
        Question $question = null
    ) {
        $this->eot = $eot;
        $this->input = $input ?: new ArgvInput;
        $this->output = $output ?: new ConsoleOutput;
        $this->question = $question;
    }

    /**
     * @param Question $question
     * @return Question
     */
    public function bind(Question $question)
    {
        /** @var callable $validator */
        $this->validator = $question->getValidator();
        return $question->setValidator($this);
    }


    public function __invoke($answer)
    {
        if ($this->eot) {
            $answer = $this->readLines($this->eot, $answer);
        } else if (0 === strpos($answer, '<<<') && ('' !== $eot = trim(substr($answer, 3)))) {
            $answer = $this->readLines($eot);
        }
        return $this->validator ? call_user_func($this->validator, $answer) : $answer;
    }

    /**
     * @param string $eot
     * @param string $firstLine
     * @return string
     */
    private function readLines($eot, $firstLine = null)
    {
        $helper = new QuestionHelper;
        $question = $this->question ?: new Question("<comment>$eot</comment>? ");
        $lines = [];
        while (true) {
            $line = $firstLine === null ? $helper->ask($this->input, $this->output, $question) : $firstLine;
            $firstLine = null;
            if (trim($line) === $eot) {
                break;
            }
            $lines[] = $line;
        };
        return implode(PHP_EOL, $lines);
    }
}
