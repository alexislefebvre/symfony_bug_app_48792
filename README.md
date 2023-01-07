# Symfony issue 48792

See https://github.com/symfony/symfony/issues/48792

## Input

```bash
git clone git@github.com:alexislefebvre/symfony_bug_app_48792.git
cd ./symfony_bug_app_48792/
composer install
# run tests
rm -rfv var/cache/* ; php bin/phpunit
```

If we delete `src/Controller/DefaultController.php` or `@required` in `AbstractController` then it works again (as noted by the author of the original issue).

It looks like `AnnotationReader::addGlobalIgnoredName('required')` is not called, so `@required` is not ignored, and it crashes.

## Output

### composer or cache:clear

```bash
Installing dependencies from lock file (including require-dev)
[…]

Run composer recipes at any time to see the status of your Symfony recipes.

Executing script cache:clear [KO]
 [KO]
Script cache:clear returned with error code 1
!!  
!!   // Clearing the cache for the dev environment with debug true                  
!!  
!!  
!!  In FileLoader.php line 178:
!!                                                                                 
!!    [Semantical Error] The class "Symfony\Contracts\Service\Attribute\Required"  
!!     is not annotated with @Annotation.                                          
!!    Are you sure this class can be used as annotation?                           
!!    If so, then you need to add @Annotation to the _class_ doc comment of "Symf  
!!    ony\Contracts\Service\Attribute\Required".                                   
!!    If it is indeed no annotation, then you need to add @IgnoreAnnotation("requ  
!!    ired") to the _class_ doc comment of method Symfony\Bundle\FrameworkBundle\  
!!    Controller\AbstractController::setContainer() in {"path":"..\/src\/Controll  
!!    er\/","namespace":"App\\Controller"} (which is being imported from "/dev/sh  
!!    m/symfony_bug_app_48792/config/routes.yaml"). Make sure there is a loader s  
!!    upporting the "attribute" type.                                              
!!                                                                                 
!!  
!!  In AnnotationException.php line 36:
!!                                                                                 
!!    [Semantical Error] The class "Symfony\Contracts\Service\Attribute\Required"  
!!     is not annotated with @Annotation.                                          
!!    Are you sure this class can be used as annotation?                           
!!    If so, then you need to add @Annotation to the _class_ doc comment of "Symf  
!!    ony\Contracts\Service\Attribute\Required".                                   
!!    If it is indeed no annotation, then you need to add @IgnoreAnnotation("requ  
!!    ired") to the _class_ doc comment of method Symfony\Bundle\FrameworkBundle\  
!!    Controller\AbstractController::setContainer().                               
!!                                                                                 
!!  
!!  cache:clear [--no-warmup] [--no-optional-warmers]
!!  
!!  
Script @auto-scripts was called via post-install-cmd
```

<details><summary>Full exception trace when running <kbd>bin/console cache:warmup -vvv</kbd></summary>

```
[critical] Error thrown while running command "cache:warmup -vvv". Message: "[Semantical Error] The class "Symfony\Contracts\Service\Attribute\Required" is not annotated with @Annotation.
Are you sure this class can be used as annotation?
If so, then you need to add @Annotation to the _class_ doc comment of "Symfony\Contracts\Service\Attribute\Required".
If it is indeed no annotation, then you need to add @IgnoreAnnotation("required") to the _class_ doc comment of method Symfony\Bundle\FrameworkBundle\Controller\AbstractController::setContainer() in {"path":"..\/src\/Controller\/","namespace":"App\\Controller"} (which is being imported from "/dev/shm/symfony_bug_app_48792/config/routes.yaml"). Make sure there is a loader supporting the "attribute" type."
[debug] Command "cache:warmup -vvv" exited with code "1"

In FileLoader.php line 178:
                                                                                                                                                                                                                             
  [Symfony\Component\Config\Exception\LoaderLoadException]                                                                                                                                                                   
  [Semantical Error] The class "Symfony\Contracts\Service\Attribute\Required" is not annotated with @Annotation.                                                                                                             
  Are you sure this class can be used as annotation?                                                                                                                                                                         
  If so, then you need to add @Annotation to the _class_ doc comment of "Symfony\Contracts\Service\Attribute\Required".                                                                                                      
  If it is indeed no annotation, then you need to add @IgnoreAnnotation("required") to the _class_ doc comment of method Symfony\Bundle\FrameworkBundle\Controller\AbstractController::setContainer() in {"path":"..\/src\/  
  Controller\/","namespace":"App\\Controller"} (which is being imported from "/dev/shm/symfony_bug_app_48792/config/routes.yaml"). Make sure there is a loader supporting the "attribute" type.                              
                                                                                                                                                                                                                             

Exception trace:
  at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/FileLoader.php:178
 Symfony\Component\Config\Loader\FileLoader->doImport() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/FileLoader.php:94
 Symfony\Component\Config\Loader\FileLoader->import() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/YamlFileLoader.php:208
 Symfony\Component\Routing\Loader\YamlFileLoader->parseImport() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/YamlFileLoader.php:99
 Symfony\Component\Routing\Loader\YamlFileLoader->load() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/FileLoader.php:163
 Symfony\Component\Config\Loader\FileLoader->doImport() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/FileLoader.php:94
 Symfony\Component\Config\Loader\FileLoader->import() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/Configurator/RoutingConfigurator.php:45
 Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator->import() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Kernel/MicroKernelTrait.php:81
 App\Kernel->configureRoutes() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Kernel/MicroKernelTrait.php:202
 App\Kernel->loadRoutes() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/ObjectLoader.php:55
 Symfony\Component\Routing\Loader\ObjectLoader->load() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/DelegatingLoader.php:37
 Symfony\Component\Config\Loader\DelegatingLoader->load() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Routing/DelegatingLoader.php:67
 Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader->load() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Routing/Router.php:65
 Symfony\Bundle\FrameworkBundle\Routing\Router->getRouteCollection() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Router.php:317
 Symfony\Component\Routing\Router->getMatcherDumperInstance() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Router.php:255
 Symfony\Component\Routing\Router->Symfony\Component\Routing\{closure}() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/ResourceCheckerConfigCacheFactory.php:36
 Symfony\Component\Config\ResourceCheckerConfigCacheFactory->cache() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Router.php:263
 Symfony\Component\Routing\Router->getMatcher() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Routing/Router.php:92
 Symfony\Bundle\FrameworkBundle\Routing\Router->warmUp() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/CacheWarmer/RouterCacheWarmer.php:42
 Symfony\Bundle\FrameworkBundle\CacheWarmer\RouterCacheWarmer->warmUp() at /dev/shm/symfony_bug_app_48792/vendor/symfony/http-kernel/CacheWarmer/CacheWarmerAggregate.php:96
 Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate->warmUp() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Command/CacheWarmupCommand.php:69
 Symfony\Bundle\FrameworkBundle\Command\CacheWarmupCommand->execute() at /dev/shm/symfony_bug_app_48792/vendor/symfony/console/Command/Command.php:312
 Symfony\Component\Console\Command\Command->run() at /dev/shm/symfony_bug_app_48792/vendor/symfony/console/Application.php:1040
 Symfony\Component\Console\Application->doRunCommand() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Console/Application.php:88
 Symfony\Bundle\FrameworkBundle\Console\Application->doRunCommand() at /dev/shm/symfony_bug_app_48792/vendor/symfony/console/Application.php:314
 Symfony\Component\Console\Application->doRun() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Console/Application.php:77
 Symfony\Bundle\FrameworkBundle\Console\Application->doRun() at /dev/shm/symfony_bug_app_48792/vendor/symfony/console/Application.php:168
 Symfony\Component\Console\Application->run() at /dev/shm/symfony_bug_app_48792/vendor/symfony/runtime/Runner/Symfony/ConsoleApplicationRunner.php:54
 Symfony\Component\Runtime\Runner\Symfony\ConsoleApplicationRunner->run() at /dev/shm/symfony_bug_app_48792/vendor/autoload_runtime.php:29
 require_once() at /dev/shm/symfony_bug_app_48792/bin/console:11

In AnnotationException.php line 36:
                                                                                                                                                                                                        
  [Doctrine\Common\Annotations\AnnotationException]                                                                                                                                                     
  [Semantical Error] The class "Symfony\Contracts\Service\Attribute\Required" is not annotated with @Annotation.                                                                                        
  Are you sure this class can be used as annotation?                                                                                                                                                    
  If so, then you need to add @Annotation to the _class_ doc comment of "Symfony\Contracts\Service\Attribute\Required".                                                                                 
  If it is indeed no annotation, then you need to add @IgnoreAnnotation("required") to the _class_ doc comment of method Symfony\Bundle\FrameworkBundle\Controller\AbstractController::setContainer().  
                                                                                                                                                                                                        

Exception trace:
  at /dev/shm/symfony_bug_app_48792/vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/AnnotationException.php:36
 Doctrine\Common\Annotations\AnnotationException::semanticalError() at /dev/shm/symfony_bug_app_48792/vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/DocParser.php:833
 Doctrine\Common\Annotations\DocParser->Annotation() at /dev/shm/symfony_bug_app_48792/vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/DocParser.php:708
 Doctrine\Common\Annotations\DocParser->Annotations() at /dev/shm/symfony_bug_app_48792/vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/DocParser.php:368
 Doctrine\Common\Annotations\DocParser->parse() at /dev/shm/symfony_bug_app_48792/vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/AnnotationReader.php:206
 Doctrine\Common\Annotations\AnnotationReader->getMethodAnnotations() at /dev/shm/symfony_bug_app_48792/vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/PsrCachedReader.php:155
 Doctrine\Common\Annotations\PsrCachedReader->fetchFromCache() at /dev/shm/symfony_bug_app_48792/vendor/doctrine/annotations/lib/Doctrine/Common/Annotations/PsrCachedReader.php:119
 Doctrine\Common\Annotations\PsrCachedReader->getMethodAnnotations() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/AnnotationClassLoader.php:364
 Symfony\Component\Routing\Loader\AnnotationClassLoader->getAnnotations() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/AnnotationClassLoader.php:129
 Symfony\Component\Routing\Loader\AnnotationClassLoader->load() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/Loader.php:48
 Symfony\Component\Config\Loader\Loader->import() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/Psr4DirectoryLoader.php:90
 Symfony\Component\Routing\Loader\Psr4DirectoryLoader->loadFromDirectory() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/Psr4DirectoryLoader.php:46
 Symfony\Component\Routing\Loader\Psr4DirectoryLoader->load() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/FileLoader.php:142
 Symfony\Component\Config\Loader\FileLoader->doImport() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/FileLoader.php:94
 Symfony\Component\Config\Loader\FileLoader->import() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/YamlFileLoader.php:208
 Symfony\Component\Routing\Loader\YamlFileLoader->parseImport() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/YamlFileLoader.php:99
 Symfony\Component\Routing\Loader\YamlFileLoader->load() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/FileLoader.php:163
 Symfony\Component\Config\Loader\FileLoader->doImport() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/FileLoader.php:94
 Symfony\Component\Config\Loader\FileLoader->import() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/Configurator/RoutingConfigurator.php:45
 Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator->import() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Kernel/MicroKernelTrait.php:81
 App\Kernel->configureRoutes() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Kernel/MicroKernelTrait.php:202
 App\Kernel->loadRoutes() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Loader/ObjectLoader.php:55
 Symfony\Component\Routing\Loader\ObjectLoader->load() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/Loader/DelegatingLoader.php:37
 Symfony\Component\Config\Loader\DelegatingLoader->load() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Routing/DelegatingLoader.php:67
 Symfony\Bundle\FrameworkBundle\Routing\DelegatingLoader->load() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Routing/Router.php:65
 Symfony\Bundle\FrameworkBundle\Routing\Router->getRouteCollection() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Router.php:317
 Symfony\Component\Routing\Router->getMatcherDumperInstance() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Router.php:255
 Symfony\Component\Routing\Router->Symfony\Component\Routing\{closure}() at /dev/shm/symfony_bug_app_48792/vendor/symfony/config/ResourceCheckerConfigCacheFactory.php:36
 Symfony\Component\Config\ResourceCheckerConfigCacheFactory->cache() at /dev/shm/symfony_bug_app_48792/vendor/symfony/routing/Router.php:263
 Symfony\Component\Routing\Router->getMatcher() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Routing/Router.php:92
 Symfony\Bundle\FrameworkBundle\Routing\Router->warmUp() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/CacheWarmer/RouterCacheWarmer.php:42
 Symfony\Bundle\FrameworkBundle\CacheWarmer\RouterCacheWarmer->warmUp() at /dev/shm/symfony_bug_app_48792/vendor/symfony/http-kernel/CacheWarmer/CacheWarmerAggregate.php:96
 Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerAggregate->warmUp() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Command/CacheWarmupCommand.php:69
 Symfony\Bundle\FrameworkBundle\Command\CacheWarmupCommand->execute() at /dev/shm/symfony_bug_app_48792/vendor/symfony/console/Command/Command.php:312
 Symfony\Component\Console\Command\Command->run() at /dev/shm/symfony_bug_app_48792/vendor/symfony/console/Application.php:1040
 Symfony\Component\Console\Application->doRunCommand() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Console/Application.php:88
 Symfony\Bundle\FrameworkBundle\Console\Application->doRunCommand() at /dev/shm/symfony_bug_app_48792/vendor/symfony/console/Application.php:314
 Symfony\Component\Console\Application->doRun() at /dev/shm/symfony_bug_app_48792/vendor/symfony/framework-bundle/Console/Application.php:77
 Symfony\Bundle\FrameworkBundle\Console\Application->doRun() at /dev/shm/symfony_bug_app_48792/vendor/symfony/console/Application.php:168
 Symfony\Component\Console\Application->run() at /dev/shm/symfony_bug_app_48792/vendor/symfony/runtime/Runner/Symfony/ConsoleApplicationRunner.php:54
 Symfony\Component\Runtime\Runner\Symfony\ConsoleApplicationRunner->run() at /dev/shm/symfony_bug_app_48792/vendor/autoload_runtime.php:29
 require_once() at /dev/shm/symfony_bug_app_48792/bin/console:11

cache:warmup [--no-optional-warmers]
```

</details>

### With PHPUnit

```bash
<!-- [Semantical Error] The class &quot;Symfony\Contracts\Service\Attribute\Required&quot; is not annotated with @Annotation.
Are you sure this class can be used as annotation?
If so, then you need to add @Annotation to the _class_ doc comment of &quot;Symfony\Contracts\Service\Attribute\Required&quot;.
If it is indeed no annotation, then you need to add @IgnoreAnnotation(&quot;required&quot;) to the _class_ doc comment of method Symfony\Bundle\FrameworkBundle\Controller\AbstractController::setContainer() in {&quot;path&quot;:&quot;..\/src\/Controller\/&quot;,&quot;namespace&quot;:&quot;App\\Controller&quot;} (which is being imported from &quot;[…]/symfony_bug_app_48792/config/routes.yaml&quot;). Make sure there is a loader supporting the &quot;attribute&quot; type. (500 Internal Server Error) -->

[…]/symfony_bug_app_48792/vendor/symfony/framework-bundle/Test/BrowserKitAssertionsTrait.php:142
[…]/symfony_bug_app_48792/vendor/symfony/framework-bundle/Test/BrowserKitAssertionsTrait.php:33
[…]/symfony_bug_app_48792/tests/src/Controller/ControllerTest.php:20

Caused by
ErrorException: [Semantical Error] The class "Symfony\Contracts\Service\Attribute\Required" is not annotated with @Annotation.
Are you sure this class can be used as annotation?
If so, then you need to add @Annotation to the _class_ doc comment of "Symfony\Contracts\Service\Attribute\Required".
If it is indeed no annotation, then you need to add @IgnoreAnnotation("required") to the _class_ doc comment of method Symfony\Bundle\FrameworkBundle\Controller\AbstractController::setContainer() in {"path":"..\/src\/Controller\/","namespace":"App\\Controller"} (which is being imported from "[…]/symfony_bug_app_48792/config/routes.yaml"). Make sure there is a loader supporting the "attribute" type. in […]/symfony_bug_app_48792/vendor/symfony/config/Loader/FileLoader.php:178
Stack trace:
#0 […]/symfony_bug_app_48792/vendor/symfony/framework-bundle/Test/BrowserKitAssertionsTrait.php(33): Symfony\Bundle\FrameworkBundle\Test\WebTestCase::assertThatForResponse()
#1 […]/symfony_bug_app_48792/tests/src/Controller/ControllerTest.php(20): Symfony\Bundle\FrameworkBundle\Test\WebTestCase::assertResponseIsSuccessful()
#2 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/src/Framework/TestCase.php(1549): App\Tests\src\Controller\ControllerTest->testControllerUserDocumentPost()
#3 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/src/Framework/TestCase.php(1155): PHPUnit\Framework\TestCase->runTest()
#4 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/src/Framework/TestResult.php(728): PHPUnit\Framework\TestCase->runBare()
#5 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/src/Framework/TestCase.php(905): PHPUnit\Framework\TestResult->run()
#6 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/src/Framework/TestSuite.php(675): PHPUnit\Framework\TestCase->run()
#7 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/src/Framework/TestSuite.php(675): PHPUnit\Framework\TestSuite->run()
#8 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/src/Framework/TestSuite.php(675): PHPUnit\Framework\TestSuite->run()
#9 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/src/TextUI/TestRunner.php(661): PHPUnit\Framework\TestSuite->run()
#10 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/src/TextUI/Command.php(144): PHPUnit\TextUI\TestRunner->run()
#11 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/src/TextUI/Command.php(97): PHPUnit\TextUI\Command->run()
#12 […]/symfony_bug_app_48792/vendor/bin/.phpunit/phpunit-9.5-0/phpunit(22): PHPUnit\TextUI\Command::main()
#13 […]/symfony_bug_app_48792/vendor/symfony/phpunit-bridge/bin/simple-phpunit.php(441): include('...')
#14 […]/symfony_bug_app_48792/bin/phpunit(18): require('...')
#15 {main}
```
