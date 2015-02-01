<?php

namespace mervick\mthaml;

use MtHaml;
use MtHaml\Support\Twig;
use Yii;
use yii\base\InvalidConfigException;

class TwigViewRenderer extends AbstractMtHamlViewRenderer
{
	/**
	 * @var string the directory or path alias pointing to where Haml cache will be stored.
	 */
	public $cachePath = '@runtime/Twig/cache';

	/**
	 * The Twig Extensions is a library that provides several useful extensions for Twig.
	 * You can find it's code at GitHub.com/twigphp/Twig-extensions.
	 * This library can be installed via Composer running the following from the command line:
	 * 		composer require twig/extensions ~1.1.0
	 * - Text: Provides useful filters for text manipulation;
	 * - I18n: Adds internationalization support via the gettext library;
	 * - Intl: Adds a filter for localization of DateTime objects;
	 * - Array: Provides useful filters for array manipulation;
	 * - Date: Adds a filter for rendering the difference between dates.
	 * @var array
	 */
	public $extensions = [];

	/**
	 * @var array MtHaml options
	 */
	public $options = [
		'enable_escaper' => false,
	];


	/**
	 * Init a haml parser instance
	 */
	public function init()
	{
		parent::init();
		$this->options['cache'] = Yii::getAlias($this->cachePath);
		$haml = new MtHaml\Environment('twig', $this->options, $this->getFilters());
		$fs = new \Twig_Loader_Filesystem([
			dirname(Yii::$app->getView()->getViewFile()),
			Yii::$app->getViewPath(),
		]);
		$loader = new MtHaml\Support\Twig\Loader($haml, $fs);

		$this->parser = new \Twig_Environment($loader, [
			'cache' => Yii::getAlias($this->cachePath),
		]);
		$this->parser->addExtension(new MtHaml\Support\Twig\Extension($haml));

		if (!empty($this->extensions)) {
			foreach ($this->extensions as $name) {
				$this->parser->addExtension($this->getExtension($name));
			}
		}
	}

	/**
	 * Get twig extension
	 * @throws InvalidConfigException
	 * @param string $name extension name
	 * @return mixed extension instance
	 */
	private function getExtension($name)
	{
		if (class_exists('Twig_Extensions_Extension_' . $name)) {
			$name = 'Twig_Extensions_Extension_' . $name;
		}
		elseif (class_exists('Twig_Extensions_Extension_' . ucfirst(strtolower($name)))) {
			$name = 'Twig_Extensions_Extension_' . ucfirst(strtolower($name));
		}
		elseif (!class_exists($name)) {
			throw new InvalidConfigException('Twig extension "' . $name . '" not declared.');
		}
		return new $name();
	}

}