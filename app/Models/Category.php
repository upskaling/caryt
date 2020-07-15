<?php
class category
{

    public function __construct(string $category_file = '../../data/category.json')
    {
        $this->category_file = $category_file;
        $this->read();
    }

    public function read()
    {

        if (!is_file($this->category_file)) {
            $this->category[] = 'Sans catégorie';
            $this->write();
        }

        $this->category = json_decode(
            file_get_contents($this->category_file),
            true
        );
    }

    public function write()
    {
        file_put_contents(
            $this->category_file,
            json_encode($this->category, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );
    }

    public function category_unique()
    {
        $this->category = array_unique($this->category);
    }

    public function add_category($new_category)
    {
        array_push($this->category, $new_category);
        $this->category_unique();
    }

    public function delete(string $id)
    {
        unset($this->category[$id]);
    }
}
