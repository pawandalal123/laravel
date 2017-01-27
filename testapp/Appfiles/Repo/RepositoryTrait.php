<?php 
namespace Appfiles\Repo;

trait RepositoryTrait {

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = array('*')) {
       // dd('in here trait');
        return $this->model->get($columns);
    }
 
    /**
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*')) {
        return $this->model->paginate($perPage, $columns);
    }
 
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {
        // dd($data);
        return $this->model->insertGetId($data);
    }
 

 
    /**
     * @param object $data
     * @return mixed
     */
    public function save($data) {
        // return $this->model->create($data);
        $data->save();
        return $data->id;
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute="id") {
        return $this->model->where($attribute, $id)->update($data);
    }
 
    /**
     * @param $id
     * @return mixed
     */
    public function delete($id) {
        return $this->model->destroy($id);
    }
 
    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = array('*')) {
        return $this->model->find($id, $columns);
    }
 
    /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($attribute, $value, $columns = array('*')) {
        return $this->model->where($attribute, '=', $value)->first($columns);
    }
 
            

            /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function getBy($condition, $columns = array('*')) {

        return $this->model->where($condition)->first($columns);
    }
 
            /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */

              /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
   

          /**
     * @param $attribute
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function getList($condition,$key,$value ) 
    {
        //print_r($condition);exit;
        return $this->model->where($condition)->lists($key,$value);
    }

 
            /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws RepositoryException
     */
    public function makeModel() 
    {
        $model = $this->app->make($this->model());
        // if (!$model instanceof Model)
        //     throw new RepositoryException("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
 
        return $this->model = $model->newQuery();
    }

}