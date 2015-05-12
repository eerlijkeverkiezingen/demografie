# DB
According to [topologie.json](./topologie.json), every file in directory [openbaar lichaam](./openbaar lichaam/) contains an JSON data-object linked to a public body, like a manicipality. Within the Netherlands there are about 400 manicipalities, 12 provinces, several waterboards and the house of representatives and senate. Each one of them have [elections](./verkiezing/) and manicipalities take part in organising elections of larger public bodies.

The database also contains some information about [political parties](./politieke partij/) and derivable [datalists](./_datalist_/).

## Datamodel
*(as found in [demografie.json](../definitie/demografie.json), populated by [topologie.json](./topologie.json))*

(..)

The used data format is JSON[plus](http://github.com/sentfanwyaerda/JSONplus) with [qTranslate](http://github.com/sentfanwyaerda/qTranslate); JSON with support of datalists and i18n strings.
