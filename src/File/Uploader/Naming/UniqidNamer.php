<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nurschool\File\Uploader\Naming;


use Nurschool\File\Util\FileNameTrait;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\ConfigurableInterface;
use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Naming\Polyfill\FileExtensionTrait;

class UniqidNamer implements NamerInterface, ConfigurableInterface
{
    use FileNameTrait;
    use FileExtensionTrait;

    private $deleteExtension = false;

    public function configure(array $options)
    {
        if (isset($options['delete_extension'])) {
            $this->deleteExtension = (bool) $options['delete_extension'];
        }
    }

    public function name($object, PropertyMapping $mapping): string
    {
        $name = $this->getUniqueFileName();

        if (!$this->deleteExtension) {
            $file = $mapping->getFile($object);
            $extension = $this->getExtension($file);
            if (\is_string($extension) && '' !== $extension) {
                $name = \sprintf('%s.%s', $name, $extension);
            }
        }

        return $name;
    }


}