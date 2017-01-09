# SilverStripe Event Mediator
A SilverStripe event emitter based on the mediator pattern, which can be applied to run a bit of code before or after any class method (i.e. triggered) using Silverstripe's [Aspects](https://docs.silverstripe.org/en/3.4/developer_guides/extending/aspects/)

## Requirements
```
"silverstripe/framework": "3.*"
"composer"
```
## Installation
To install, run below from root of SilverStripe installation:
```bash 
> composer require fspringveldt/silverstripe-event-mediator
``` 

http://**your-site-url**?flush=1 once composer is complete the flush the manifest.

##Configuration 1 - Extension based (default)
Installing the module adds the ```eventMediator\EventExtension``` to DataObject. 
Let's say after each call to ```$A->foo()``` you'd like to fire off a call to ```$B->bar()```, the following added to your composer.yml sets up the events:

```yaml
Injector:
  eventMediator\EventMediator':
    properties:
      events:
        foo:
          triggerBar:
            class: B
            method: bar
```

then in ```$A->foo()``` do the following:

```php
class A{
    function foo(){
        //foo funciton body
        
        $this->emit(__FUNCTION__);
    }
}
```

##Configuration 2 - Aspect using AopProxyService
Let's say after each call to ```$A->foo()``` you'd like to fire off a call to ```$B->bar()```, the following in your composer.yml should do the trick:
```yaml
Injector:
  'eventMediator\EventMediator':
    properties:
      events:
        foo:
          triggerBar:
            class: B
            method: bar
  ProxiedA:
    class: A
  A:
    class: AopProxyService
    properties:
      proxied: %$ProxiedA
      afterCall:
        foo:
          - %$eventMediator\EventMediator'
```
All parameters from ```$A->foo()``` are sent through ```$B->foo()``` for your perusal.  
