KnpMenu-Laravel
============
Laravel 5 package to create navigation menus, based on [KnpLabs/KnpMenu](https://github.com/KnpLabs/KnpMenu).

### Installation
Add to your composer.json file
```json
"enimiste/knp-menu-laravel": "2.*"
```

### Register the package

In config/app.php add the service provider and alias.

```php
Dowilcox\KnpMenu\MenuServiceProvider::class,
```

```php
'Menu' => Dowilcox\KnpMenu\Facades\Menu::class,
```

To Access the menu service directly :
```php
$menu_builder = app('knp_menu.menu');
```

#### Publish config
```bash
php artisan vendor:publish --tag=knp_menu
```

### Custom Rendrer
To define your custom renderer :  
- Implements the interface "Knp\Menu\Renderer\RendererInterface"
- Register a binding to "knp_menu.renderer" that returns a new instance of your new custom renderer. To get the matcher use `$app["knp_menu.matcher"]`  

### Custom Voter
To add custom Voter you implement the interface "Dowilcox\KnpMenu\Voter\OrderedVoterInterface" and register it in the service container with the tag "knp_menu.voter"  
Set the lower order for Voter that should be executed in first.  
It is recommended order values between 0 and than 100.  
Your Voters will be executed first before the built in ones.  
- RouteNameVoter (100)
- UriVoter (200 and 300)

### Blade directives
- @rendermenu("main" [, {"firstClass":"first2","lastClass":"last2"}]) or @rendermenu("main" [, "config_name"]): It renders the menu defined by the name "menu_name". It is a shortcut to `echo \Menu::render(\Menu::get('menu_name'))`
- @menu('menu_name') .... @endmenu : between these two directives you have access to a variable named `$menu` holding the menu defined by the name "menu_name". This object is an instance of `Knp\Menu\MenuItem`

### Example

```php
$menu = Menu::create('main-menu', ['childrenAttributes' => ['class' => 'nav']]);

/*
* This is the list of possible options for a menu item :
*
*     'uri' => null,
*     'label' => null,
*     'attributes' => array(),
*     'linkAttributes' => array(),
*     'childrenAttributes' => array(),
*     'labelAttributes' => array(),
*     'extras' => array(),
*     'current' => null,
*     'display' => true,
*     'displayChildren' => true,
*/

$menu->addChild('Home', [
'uri' => url('/'),
'attributes' => [
    'class'=>'your_css_class',
 ],
 'extras' => [
    'routes' => [
        ['route' => 'route_name_1'],
        ['route' => 'route_name_2'],
    ]
 ]
]);
$menu->addChild('Users', ['uri' => route('admin.users.index')]);
$menu->addChild('Roles', ['uri' => route('admin.roles.index')]);
$menu->addChild('Menu', ['uri' => url('menu')]);

echo Menu::render($menu);
//Or
echo Menu::render($menu, $custom_render_options);//$custom_render_options is an array
```

Will output:
```html
<ul class="nav">
  <li class="first">
    <a href="http://localhost:8000">Home</a>
  </li>
  <li class='your_css_class'>
    <a href="http://localhost:8000/admin/users">Users</a>
  </li>
  <li class='your_css_class'>
    <a href="http://localhost:8000/admin/roles">Roles</a>
  </li>
  <li class="current active last your_css_class">
    <a href="http://localhost:8000/menu">Menu</a>
  </li>
</ul>
```
