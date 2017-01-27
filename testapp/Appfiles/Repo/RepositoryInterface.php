<?php 
namespace Appfiles\Repo;

interface RepositoryInterface {

	public function all($columns = array('*'));
 
    public function paginate($perPage = 15, $columns = array('*'));
 
    public function create(array $data);
 
    public function update(array $data, $id);

 
    public function delete($id);
 
    public function find($id, $columns = array('*'));
 
    public function findBy($field, $value, $columns = array('*'));
    public function getBy($condition, $columns = array('*'));
    public function getList($condition, $key,$value);
    #public function getallBy($condition, $columns = array('*'));
}