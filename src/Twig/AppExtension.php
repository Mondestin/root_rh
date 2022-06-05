<?php

/**
 * Author: Johan Mickaël
 * Description: This is a custom twig extension for adding custom css classes inside an html element
 */

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
	protected $requestStack;

	public function __construct(RequestStack $requestStack)
	{
		$this->requestStack = $requestStack;
	}

	public function getFilters()
	{
		return [
			new TwigFilter('appendStyle', [$this, 'appendStyle']),
			new TwigFilter('unit', [$this, 'setUnit']),
		];
	}

	public function appendStyle(mixed $routes, $class = ''): string
	{
		$currentRoute = $this->requestStack->getCurrentRequest()->get('_route');
		foreach ($routes as $route) {
			if (str_starts_with($currentRoute, $route)) {
				return $class;
			}
		}
		return "";
	}
	public function setUnit($item, $unit = ''): string
	{
		return $item . ' ' . $unit;
	}
}
