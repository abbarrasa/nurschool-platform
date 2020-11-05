<?php

namespace Nurschool\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Nurschool\Form\Type\RoleListType;

class RoleListField implements FieldInterface
{
    use FieldTrait;

    public const OPTION_ALLOW_MULTIPLE_CHOICES = 'allowMultipleChoices';
    public const OPTION_CHOICES = 'choices';
    public const OPTION_RENDER_AS_BADGES = 'renderAsBadges';
    public const OPTION_RENDER_EXPANDED = 'renderExpanded';
    public const OPTION_WIDGET = 'widget';

    public const VALID_BADGE_TYPES = ['success', 'warning', 'danger', 'info', 'primary', 'secondary', 'light', 'dark'];

    public const WIDGET_AUTOCOMPLETE = 'autocomplete';
    public const WIDGET_NATIVE = 'native';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setTemplateName('crud/field/choice')
            ->setFormType(RoleListType::class)
            ->addCssClass('field-select')
            ->setCustomOption(self::OPTION_ALLOW_MULTIPLE_CHOICES, true)
            ->setCustomOption(self::OPTION_RENDER_EXPANDED, true)
            ->setCustomOption(self::OPTION_RENDER_AS_BADGES, null)
            ->setCustomOption(self::OPTION_WIDGET, null);
    }

    public function allowMultipleChoices(bool $allow = true): self
    {
        $this->setCustomOption(self::OPTION_ALLOW_MULTIPLE_CHOICES, $allow);

        return $this;
    }

    /**
     * Possible values of $badgeSelector:
     *   * true: all values are displayed as 'secondary' badges
     *   * false: no badges are displayed; values are displayed as regular text
     *   * array: [$fieldValue => $badgeType, ...] (e.g. ['foo' => 'primary', 7 => 'warning', 'cancelled' => 'danger'])
     *   * callable: function(FieldDto $field): string { return '...' }
     *     (e.g. function(FieldDto $field) { return $field->getValue() < 10 ? 'warning' : 'primary'; }).
     *
     * Possible badge types: 'success', 'warning', 'danger', 'info', 'primary', 'secondary', 'light', 'dark'
     */
    public function renderAsBadges($badgeSelector = true): self
    {
        if (!\is_bool($badgeSelector) && !\is_array($badgeSelector) && !\is_callable($badgeSelector)) {
            throw new \InvalidArgumentException(sprintf('The argument of the "%s" method must be a boolean, an array or a closure ("%s" given).', __METHOD__, \gettype($badgeSelector)));
        }

        if (\is_array($badgeSelector)) {
            foreach ($badgeSelector as $fieldValue => $badgeType) {
                if (!\in_array($badgeType, self::VALID_BADGE_TYPES, true)) {
                    throw new \InvalidArgumentException(sprintf('The values of the array passed to the "%s" method must be one of the following valid badge types: "%s" ("%s" given).', __METHOD__, implode(', ', self::VALID_BADGE_TYPES), $badgeType));
                }
            }
        }

        $this->setCustomOption(self::OPTION_RENDER_AS_BADGES, $badgeSelector);

        return $this;
    }

    public function renderAsNativeWidget(bool $asNative = true): self
    {
        $this->setCustomOption(self::OPTION_WIDGET, $asNative ? self::WIDGET_NATIVE : self::WIDGET_AUTOCOMPLETE);

        return $this;
    }

    public function renderExpanded(bool $expanded = true): self
    {
        $this->setCustomOption(self::OPTION_RENDER_EXPANDED, $expanded);

        return $this;
    }
}