# Datos geográficos

`chile-regions.geojson` deriva de la capa **División regional** publicada por el Sistema Integrado de Información Territorial de la Biblioteca del Congreso Nacional de Chile:

https://www.bcn.cl/siit/mapas_vectoriales

La capa fue reproyectada a WGS84, limitada a las 16 regiones y simplificada para su uso en la detección referencial de stock regional. La BCN permite usar libremente estos mapas citando la fuente y advierte que no deben emplearse para trabajos que exijan precisión geodésica.

La aplicación utiliza estos polígonos únicamente para convertir una coordenada autorizada por el usuario en un código regional. No los utiliza para validar domicilios ni límites legales.
