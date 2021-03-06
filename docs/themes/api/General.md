# General - *DSJAS Theme API Documentation*

* **API Name:** General
* **Description/use:** Used for querying general configuration and performing generalized theme tasks
* **File Name:** General.php
* **Full Path:** /include/api/theme/General.php
* **Anchor point specific?** No
* **Provides/exposes:** Self-contained functions

## ```getCurrentThemeName()```

> [!CAUTION]
> **Possible negative performance hit!** This function loads and parses configuration from disc. After the first call, configuration is cached.

Returns a string representation of the current theme name. If the default theme is enabled, 'default' will be returned.

* **Parameters:** None
* **Returns:** ```string``` The string representation of the current theme name

### Example

```php
echo ("This theme is called: " . gerCurrentThemeName());
```

## ```getBankName()```

> [!CAUTION]
> **Possible negative performance hit!** This function loads and parses configuration from disc. After the first call, configuration is cached.

Returns a string representation of the configured bank name from the user's settings.

* **Parameters:** None
* **Returns:** ```string``` The string representation of the bank's configured name

### Example

```html
<h1>Welcome to <?= getBankName(); ?> - where money is really important</h1>
```

## ```getBankURL()```

> [!CAUTION]
> **Possible negative performance hit!** This function loads and parses configuration from disc. After the first call, configuration is cached.

> [!IMPORTANT]
> Although this is meant to be the bank's DNS domain, we recommend not linking to this, and instead using a single forward slash to instruct the client to request from the root. We cannot guarantee that this field is valid or setup correctly by the user.

Returns a string representation of the user provided DNS hostname of the server. In practice, this isn't really needed, as we can just link to the root of the server. But, this is stull useful if you want to format a link to show the destination or an email address etc.

* **Parameters:** None
* **Returns:** ```string``` A string representation of the user provided bank DNS domain

### Example

```php
echo ("Contact the bank via email: support@" . getBankURL())
```

## ```addModuleDescriptor()```

> [!TIP]
> Please see "Working with Modules" in the main theme documentation

Adds a DOM Module Descriptor into the theme's content. This is used to tell modules where they can place their content. If unused, some modules will break or fail to load.

It's important to use module descriptors where possible, as many modules rely on them to work correctly.

* **Parameter - ```string $descriptorName```:** Please see the documentation mentioned above for info on what vales can be used here
* **Returns:** ```void```

### Example

```html
<div class="navbar navbar-fluid">
    <p>This is my NavBar!</p>
    <?php addModuleDescriptor("nav_bar"); ?>
</div>

<div id="alerts-go-here">
    <?php addModuleDescriptor("alert_area"); ?>
</div>
```

## ```updateStatistic()```

> [!CAUTION]
> **Possible negative performance hit!** This function communicates with the database a maximum of twice, and a minimum of once per call. Calls are not cached and database queries are not aggregated (will be sent as requested, when requested).

Updates a theme-provided statistic (see [here](https://github.com/DSJAS/DSJAS/blob/master/docs/administration/Statistics.md#theme-statistics)) with either a generated value or the specified value.

DSJAS will reach out to the database to update the specified statistic where possible, and will automatically register it if it has not been updater previously. The provided name will be used to generate a database-friendly identifier for the statistic which the user will never see. The provided name will be used as the user-friendy label in the admin dashboard.

* **Parameter - ```string $name```:** The user-friendly name for this statistic. Will be registered if not previously used
* **Parameter - ```int $value```:** Optional: The new value for the statistic. For all but number statistics, this is ignored and the true value will be generated by the database dynamically
* **Parameter - ```int $type```:** Enumerator: Denotes the statistics type. Can either be ```STATISTICS_TYPE_COUNT```, ```STATISTICS_TYPE_NUMBER``` or ```STATISTICS_TYPE_TIMESTAMP```
* **Parameter - ```string $category```:** Theme category to be placed sorted in via the admin panel. If not provided, a default value of "Theme provided" will be used.
