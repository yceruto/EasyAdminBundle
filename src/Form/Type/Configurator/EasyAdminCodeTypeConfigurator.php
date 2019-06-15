<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator;

use EasyCorp\Bundle\EasyAdminBundle\Form\Type\EasyAdminCodeType;
use Symfony\Component\Form\FormConfigInterface;

/**
 * This configurator is applied to any form field of type 'code'.
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class EasyAdminCodeTypeConfigurator implements TypeConfiguratorInterface
{
    private static $supportedLanguages = ['css', 'dockerfile', 'js', 'javascript', 'markdown', 'nginx', 'php', 'shell', 'sql', 'twig', 'xml', 'yaml-frontmatter', 'yaml'];

    /**
     * {@inheritdoc}
     */
    public function configure($name, array $options, array $metadata, FormConfigInterface $parentConfig)
    {
        if (isset($metadata['height']) && !\is_int($metadata['height'])) {
            throw new \InvalidArgumentException(\sprintf('The "height" option in the "%s" field of the "%s" entity must be an integer, "%s" data type passed,', $name, $parentConfig->getOption('entity'), \gettype($metadata['height'])));
        }

        if (isset($metadata['tab_size']) && !\is_int($metadata['tab_size'])) {
            throw new \InvalidArgumentException(\sprintf('The "tab_size" option in the "%s" field of the "%s" entity must be an integer, "%s" data type passed,', $name, $parentConfig->getOption('entity'), \gettype($metadata['tab_size'])));
        }

        if (isset($metadata['indent_with_tabs']) && !\is_bool($metadata['indent_with_tabs'])) {
            throw new \InvalidArgumentException(\sprintf('The "indent_with_tabs" option in the "%s" field of the "%s" entity must be a boolean, "%s" data type passed,', $name, $parentConfig->getOption('entity'), \gettype($metadata['indent_with_tabs'])));
        }

        if (isset($metadata['language']) && !\in_array($metadata['language'], self::$supportedLanguages)) {
            throw new \InvalidArgumentException(\sprintf('The "language" option in the "%s" field of the "%s" entity must be one of the following supported languages: "%s".', $name, $parentConfig->getOption('entity'), \implode(', ', self::$supportedLanguages)));
        }

        $options['attr']['height'] = $metadata['height'] ?? null;
        $options['attr']['tabSize'] = $metadata['tab_size'] ?? 4;
        $options['attr']['indentWithTabs'] = $metadata['indent_with_tabs'] ?? false;

        // the code editor can't autodetect the language, so let's use 'markdown' when
        // no language is selected explicitly (because it's the most similar to regular text)
        // also, define some shortcuts for better UX (e.g. 'js' === 'javascript')
        if (!isset($metadata['language'])) {
            $options['attr']['language'] = 'markdown';
        } else {
            $options['attr']['language'] = 'js' === $metadata['language'] ? 'javascript' : $metadata['language'];
        }

        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($type, array $options, array $metadata)
    {
        return \in_array($type, ['code', EasyAdminCodeType::class], true);
    }
}
