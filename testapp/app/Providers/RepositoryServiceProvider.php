<?php 
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
class RepositoryServiceProvider extends ServiceProvider {
    public function boot() 
    {
    }

        /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Appfiles\Repo\CategoryInterface', 'Appfiles\Repo\CategoryRepository');
        $this->app->bind('Appfiles\Repo\SubcategoryInterface', 'Appfiles\Repo\SubcategoryRepository');
        $this->app->bind('Appfiles\Repo\UsersInterface', 'Appfiles\Repo\UsersRepository');
        $this->app->bind('Appfiles\Repo\MediadetailInterface', 'Appfiles\Repo\MediadetailRepository');
        $this->app->bind('Appfiles\Repo\UserdetailInterface', 'Appfiles\Repo\UserdetailRepository');
        $this->app->bind('Appfiles\Repo\FeaturesInterface', 'Appfiles\Repo\FeaturesRepository');
        $this->app->bind('Appfiles\Repo\AdminfeatureInterface', 'Appfiles\Repo\AdminfeatureRepository');
        $this->app->bind('Appfiles\Repo\CityInterface', 'Appfiles\Repo\CityRepository');
        $this->app->bind('Appfiles\Repo\CountryInterface', 'Appfiles\Repo\CountryRepository');
        $this->app->bind('Appfiles\Repo\StateInterface', 'Appfiles\Repo\StateRepository');
        $this->app->bind('Appfiles\Repo\ArticlesInterface', 'Appfiles\Repo\ArticlesRepository');
        $this->app->bind('Appfiles\Repo\DiscussionsInterface', 'Appfiles\Repo\DiscussionRepository');
        $this->app->bind('Appfiles\Repo\DocumentsInterface', 'Appfiles\Repo\DocumentsRepository');
        $this->app->bind('Appfiles\Repo\DocumentstypeInterface', 'Appfiles\Repo\DocumentstypeRepository');
        $this->app->bind('Appfiles\Repo\DocumentsshareInterface', 'Appfiles\Repo\DocumentsshareRepository');
        


    }
}