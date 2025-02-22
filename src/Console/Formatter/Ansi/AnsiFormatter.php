<?php

declare(strict_types=1);

namespace ExtendsSoftware\ExaPHP\Console\Formatter\Ansi;

use ExtendsSoftware\ExaPHP\Console\Formatter\Ansi\Exception\ColorNotSupported;
use ExtendsSoftware\ExaPHP\Console\Formatter\Ansi\Exception\FormatNotSupported;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\Black\Black;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\Blue\Blue;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\ColorInterface;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\Cyan\Cyan;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\DarkGray\DarkGray;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\Green\Green;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightBlue\LightBlue;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightCyan\LightCyan;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightGray\LightGray;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightGreen\LightGreen;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightMagenta\LightMagenta;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightRed\LightRed;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\LightYellow\LightYellow;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\Magenta\Magenta;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\Red\Red;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\White\White;
use ExtendsSoftware\ExaPHP\Console\Formatter\Color\Yellow\Yellow;
use ExtendsSoftware\ExaPHP\Console\Formatter\Format\Blink\Blink;
use ExtendsSoftware\ExaPHP\Console\Formatter\Format\Bold\Bold;
use ExtendsSoftware\ExaPHP\Console\Formatter\Format\Dim\Dim;
use ExtendsSoftware\ExaPHP\Console\Formatter\Format\FormatInterface;
use ExtendsSoftware\ExaPHP\Console\Formatter\Format\Hidden\Hidden;
use ExtendsSoftware\ExaPHP\Console\Formatter\Format\Reverse\Reverse;
use ExtendsSoftware\ExaPHP\Console\Formatter\Format\Underlined\Underlined;
use ExtendsSoftware\ExaPHP\Console\Formatter\FormatterInterface;

use function array_diff;
use function array_merge;
use function implode;
use function is_int;
use function sprintf;
use function str_pad;
use function str_repeat;
use function substr;

class AnsiFormatter implements FormatterInterface
{
    /**
     * Text foreground color.
     *
     * @var int
     */
    private int $foreground = 39;

    /**
     * Text background color.
     *
     * @var int
     */
    private int $background = 49;

    /**
     * Text format.
     *
     * @var mixed[]
     */
    private array $format = [];

    /**
     * Maximum text width.
     *
     * @var int|null
     */
    private ?int $width;

    /**
     * Text indent.
     *
     * @var int|null
     */
    private ?int $indent;

    /**
     * Color mapping.
     *
     * @var int[]
     */
    private array $colors = [
        Black::NAME => 30,
        Red::NAME => 31,
        Green::NAME => 32,
        Yellow::NAME => 33,
        Blue::NAME => 34,
        Magenta::NAME => 35,
        Cyan::NAME => 36,
        LightGray::NAME => 37,
        DarkGray::NAME => 90,
        LightRed::NAME => 91,
        LightGreen::NAME => 92,
        LightYellow::NAME => 93,
        LightBlue::NAME => 94,
        LightMagenta::NAME => 95,
        LightCyan::NAME => 96,
        White::NAME => 97,
    ];

    /**
     * Format mapping.
     *
     * @var int[]
     */
    private array $formats = [
        Bold::NAME => 1,
        Dim::NAME => 2,
        Underlined::NAME => 4,
        Blink::NAME => 5,
        Reverse::NAME => 7,
        Hidden::NAME => 8,
    ];

    /**
     * Set default values for builder.
     */
    public function __construct()
    {
        $this->resetBuilder();
    }

    /**
     * @inheritDoc
     */
    public function setForeground(ColorInterface $color): FormatterInterface
    {
        return $this->setColor($color);
    }

    /**
     * @inheritDoc
     */
    public function setBackground(ColorInterface $color): FormatterInterface
    {
        return $this->setColor($color, true);
    }

    /**
     * @inheritDoc
     */
    public function addFormat(FormatInterface $format, bool $remove = null): FormatterInterface
    {
        return $this->setFormat($format);
    }

    /**
     * @inheritDoc
     */
    public function removeFormat(FormatInterface $format): FormatterInterface
    {
        return $this->setFormat($format, true);
    }

    /**
     * @inheritDoc
     */
    public function setFixedWidth(int $length = null): FormatterInterface
    {
        $this->width = $length;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTextIndent(int $length = null): FormatterInterface
    {
        $this->indent = $length;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function create(string $text): string
    {
        if (!empty($this->format)) {
            $format = implode(';', $this->format);
        } else {
            $format = 0;
        }

        if (is_int($this->width)) {
            $text = str_pad(substr($text, 0, $this->width), $this->width);
        }

        if (is_int($this->indent)) {
            $text = str_repeat(' ', $this->indent) . $text;
        }

        $formatted = sprintf(
            "\e[%s;%d;%dm%s\e[0m",
            $format,
            $this->foreground,
            $this->background,
            $text
        );

        $this->resetBuilder();

        return $formatted;
    }

    /**
     * Reset builder with default values.
     */
    private function resetBuilder(): void
    {
        $this->foreground = 39;
        $this->background = 49;
        $this->format = [];
        $this->width = null;
        $this->indent = null;
    }

    /**
     * Set format for text.
     *
     * When remove is true, format will be removed. An exception will be thrown when format is unknown.
     *
     * @param FormatInterface $format
     * @param bool|null       $remove
     *
     * @return FormatterInterface
     * @throws FormatNotSupported
     */
    private function setFormat(FormatInterface $format, bool $remove = null): FormatterInterface
    {
        $name = $format->getName();
        if (!isset($this->formats[$name])) {
            throw new FormatNotSupported($format);
        }

        $code = $this->formats[$name];
        if ($remove) {
            $this->format = array_diff($this->format, [$code]);
        } else {
            $this->format = array_merge($this->format, [$code]);
        }

        return $this;
    }

    /**
     * Set color code for foreground or background.
     *
     * An exception will be thrown when color is unknown.
     *
     * @param ColorInterface $color
     * @param bool|null      $background
     *
     * @return FormatterInterface
     * @throws ColorNotSupported
     */
    private function setColor(ColorInterface $color, bool $background = null): FormatterInterface
    {
        $name = $color->getName();
        if (!isset($this->colors[$name])) {
            throw new ColorNotSupported($color);
        }

        if ($background) {
            $this->background = $this->colors[$name] + 10;
        } else {
            $this->foreground = $this->colors[$name];
        }

        return $this;
    }
}
