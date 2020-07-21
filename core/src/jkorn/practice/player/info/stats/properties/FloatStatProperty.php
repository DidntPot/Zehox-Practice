<?php

declare(strict_types=1);

namespace jkorn\practice\player\info\stats\properties;


use jkorn\practice\player\info\stats\IStatProperty;

class FloatStatProperty implements IStatProperty
{

    /** @var float */
    private $value;
    /** @var string */
    private $localized;

    /** @var bool */
    private $saved = true;

    public function __construct(string $localized, bool $saved, $default = 0.0)
    {
        $this->localized = $localized;
        $this->value = $default;
        $this->saved = $saved;
    }

    /**
     * @return string
     *
     * Gets the localized name of the property.
     */
    public function getLocalized(): string
    {
        return $this->localized;
    }

    /**
     * @return mixed
     *
     * Gets the value of the player property.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return bool
     *
     * Sets the value of the player property.
     */
    public function setValue($value): bool
    {
        $oldValue = $this->value;
        $this->value = $value;
        return $oldValue !== $value;
    }

    /**
     * @return bool
     *
     * Determines whether or not we want to save this statistic.
     */
    public function doSave(): bool
    {
        return $this->saved;
    }

    /**
     * @param bool $save
     *
     * Sets whether or not the statistic should be saved.
     */
    public function setSave(bool $save): void
    {
        $this->saved = $save;
    }
}