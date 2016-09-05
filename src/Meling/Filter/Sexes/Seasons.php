<?php
namespace Meling\Filter\Sexes;

/**
 * Class Seasons
 * @package Meling\Filter\Sexes
 * @since   2.0
 */
class Seasons
{
    /**
     * @var \Meling\Filter
     * @since 2.0
     */
    protected $filter;

    /**
     * @var array
     * @since 2.0
     */
    protected $selected;

    /**
     * @var Sex
     * @since 2.0
     */
    protected $sex;

    /**
     * @var Seasons\Season[]
     * @since 2.0
     */
    private $seasons;

    /**
     * Seasons constructor.
     * @param \Meling\Filter $filter
     * @param Sex            $sex
     * @param array          $selected
     * @since    2.0
     */
    public function __construct(\Meling\Filter $filter, Sex $sex, $selected = [])
    {
        $this->filter   = $filter;
        $this->selected = $selected;
        $this->sex      = $sex;
    }

    /**
     * @return Seasons\Season[]
     * @since 2.0
     */
    public function asArray()
    {
        $this->requireSeasons();

        return $this->seasons;
    }

    /**
     * @param mixed $id
     * @return Seasons\Season
     * @throws \Exception
     * @since 2.0
     */
    public function get($id = null)
    {
        $this->requireSeasons();
        if(array_key_exists($id, $this->seasons)) {
            return $this->seasons[$id];
        }
        throw new \Exception('Season ' . $id . ' does not exist');
    }

    /**
     * @return array
     * @since 2.0
     */
    public function selected()
    {
        return $this->selected;
    }

    protected function requireSeasons()
    {
        if($this->seasons !== null) {
            return;
        }
        $query = $this->filter->connection()->selectQuery();
        $query->fields(
            array(
                'seasons.id',
                'seasons.name',
                'products.sexId',
            )
        );
        $query->table('seasons');
        $query->join('products');
        $query->on('products.seasonId', 'seasons.id');
        // Только с остатками
        $query->join('restOptions', 'restProducts');
        $query->on('restProducts.productId', 'products.id');

        $query->join('options');
        $query->on('options.id', 'restProducts.optionId');
        // Только в доступных магазинах
        $query->join('shops');
        $query->on('shops.id', 'restProducts.shopId');
        $query->where('shops.publish', 1);
        $query->where('shops.active', 1);
        $query->where('shops.hidden', 0);
        // Только с изображениями
        $query->join('productImages');
        $query->on('productImages.productId', 'products.id');
        // Только с половой принадлежностью
        if($this->sex->id() !== 3003) {
            $query->where('products.sexId', $this->sex->id());
        }
        // Только опубликованные бренды
        $query->where('seasons.publish', 1);
        $query->orderAscendingBy('seasons.name');
        $query->groupBy('seasons.id');
        /** @var \PHPixie\Database\Result $result */
        $result  = $query->execute();
        $seasons = [];
        foreach($result->asArray() as $season) {
            $seasons[$season->id] = new Seasons\Season($season->id, $season->name, in_array($season->id, $this->selected()));
        }
        $this->seasons = $seasons;
    }

}

