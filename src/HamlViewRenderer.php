<?php

namespace mervick\mthaml;

use Yii;
use MtHaml;

class HamlViewRenderer extends AbstractMtHamlViewRenderer
{

	/**
	 * @var string the directory or path alias pointing to where Haml cache will be stored.
	 */
	public $cachePath = '@runtime/Haml/cache';

	/**
	 * Init a haml parser instance
	 */
	public function init()
	{
		parent::init();
		$haml = new MtHaml\Environment('php', $this->options, $this->getFilters());
		$this->parser = new \mervick\mthaml\override\Executor($haml, [
			'cache' => Yii::getAlias($this->cachePath),
			'debug' => $this->debug
		]);
	}

}