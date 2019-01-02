<?php

namespace App\Infrastructure\Component;


use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

class Time implements TimeInterface
{

    /**
     * The translator.
     *
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * Time constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTime():int {
        return $this->requestStack->getCurrentRequest()->server->get('REQUEST_TIME');
    }

    /**
     * @inheritdoc
     */
    public function getTimezones(): array
    {
        $zoneList = timezone_identifiers_list();
        $zones = [];
        foreach ($zoneList as $zone) {
            // Because many time zones exist in PHP only for backward compatibility
            // reasons and should not be used, the list is filtered by a regular
            // expression.
            if (preg_match(
                '!^((Africa|America|Antarctica|Arctic|Asia|Atlantic|Australia|Europe|Indian|Pacific)/|UTC$)!',
                $zone
            )) {
                $zones[$zone] = $this->translator->trans(
                    '@zone',
                    ['@zone' => $this->translator->trans(str_replace('_', ' ', $zone))]
                );
            }
        }
        asort($zones);

        return $zones;
    }

    /**
     * @inheritdoc
     */
    public function getGroupedTimezones(array $zones): array
    {
        $grouped_zones = $this->getgroupedZones($zones);
        $this->sortGroupedZones($grouped_zones);

        return $grouped_zones;
    }

    /**
     * @param array $zones
     * @return array
     */
    protected function getgroupedZones(array $zones): array
    {
        $grouped_zones = [];
        foreach ($zones as $key => $value) {
            $split = explode('/', $value);
            $city = array_pop($split);
            $region = array_shift($split);
            if (!empty($region)) {
                $grouped_zones[$region][$key] = empty($split) ? $city : $city.' ('.implode('/', $split).')';
            } else {
                $grouped_zones[$key] = $value;
            }
        }

        return $grouped_zones;
    }

    /**
     * @param $grouped_zones
     */
    protected function sortGroupedZones($grouped_zones): void
    {
        foreach ($grouped_zones as $key => $value) {
            if (\is_array($grouped_zones[$key])) {
                asort($grouped_zones[$key]);
            }
        }
    }

}