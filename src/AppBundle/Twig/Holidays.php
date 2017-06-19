<?php
namespace AppBundle\Twig;

class Holidays extends \Twig_Extension
{
    public function getFunctions()
    {
        $function = function () {
            $result = $this->greetingBuild();
            return $result;
        };
        return array(
            new \Twig_SimpleFunction('greeting', $function),
        );
    }

    public function greetingBuild($holiday = null)
    {
        $today = date('m.d');

        if ($today =='01.30') {
            $holiday = "HAPPY NEW YEAR!";
        } elseif ($today =='02.24') {
            $holiday = "Happy Valentines Day!";
        } elseif ($today == '06.19') {
            $holiday = "Extension created!";
        }
        return $holiday;
    }

    public function getName()
    {
        return 'greeting';
    }
}
