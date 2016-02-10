<?php
/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */
namespace manugarciaes\SearchBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class SearchParamsTwigExtension
 * @package SearchBundle\Twig
 */
class SearchParamsTwigExtension extends \Twig_Extension
{
    protected $request;
    protected $generator;

    /**
     * @param RequestStack          $requestStack
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(RequestStack $requestStack, UrlGeneratorInterface $generator)
    {
        $this->request = $requestStack->getMasterRequest();
        $this->generator = $generator;
    }
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('add_param', array($this, 'addParam')),
            new \Twig_SimpleFunction('delete_param', array($this, 'deleteParam')),
        );
    }

    /**
     * @param string $url
     * @param array  $params
     * @return mixed
     */
    public function addParam($url, $params)
    {
        $get = $this->request->query->all();

        foreach ($params as $field => $value) {
            $get[$field] = $value;
        }

        return $this->getPath($url, $get);
    }

    /**
     * @param string $url
     * @param array  $params
     * @param array  $arrays
     * @return mixed
     */
    public function deleteParam($url, $params, $arrays = [])
    {

        $params = $params + $arrays;
        $get = $this->request->query->all();

        foreach ($params as $key => $value) {
            // get paramters with multiple selections and delete one value
            if (isset($get[$key])) {
                unset($get[$key][$value]);
            } else {
                unset($get[$value]);
            }
        }

        return $this->getPath($url, $get);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "search_params";
    }

    /**
     * @param string $route
     * @param array  $parameters
     * @param bool   $relative
     * @return mixed
     */
    public function getPath($route, $parameters = array(), $relative = false)
    {
        // if route is a url
        if (substr($route, 0, 5) == 'http:') {
            return $route.'?'.http_build_query($parameters);
        }

        return $this->generator->generate($route, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
    }
}
