# Table of Contents

1. [OGC API - Feature](#ogc-api-feature)
2. [Server API](#server-api)
   - [Drivers](#drivers)
   - [Services](#services)
   - [Datasources](#datasources)
   - [Feature Classes](#feature-classes)


## OGC API Feature

The requirements for the OGC API Feature are found in the [OGC API - Features - Part 1: Core corrigendum](https://docs.opengeospatial.org/is/17-069r4/17-069r4.html)

## Server API

### Drivers
| Requirement(s) | Description                                                                                                                  | Status |
|----------------|------------------------------------------------------------------------------------------------------------------------------|--------|
| R1.A           | The server **SHALL** support the HTTP GET method at path `{root}/api/drivers                                                 | 🟢     |
| R2.A           | The successful execution of the operation **SHALL** be reported as a reponse with a HTTP status code `200`                   | 🟢     |
| R2.B           | The response **SHALL** be an array of objects based upon the OpenAPI 3.0 schema, [drivers.yaml](openapi/schema/driver.yaml). | 🟢     |

### Datasources
| Requirement(s) | Description                                                                                                                                                                                                                                                                                                                                                                                  | Status |
|----------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|--------|
| R3.A           | The server **SHALL** support the HTTP GET method at path `{root}/api/datasources`                                                                                                                                                                                                                                                                                                            | 🟢     |
| R4.A           | The successful execution of the operation **SHALL** be reported as a reponse with a HTTP status code `200`                                                                                                                                                                                                                                                                                   | 🟢     |
| R2.B           | The response **SHALL** be an objects based upon the OpenAPI 3.0 schema with the following properties: <ol><li>`datasources` which **MUST** be an array of objects based upon the OpenAPI 3.0 schema, [datasource.yaml](openapi/schema/datasource.yaml).</li><li>`links` which **MUST** be an array of object base upoon the OpenAPI 3.0 schema, [link.yaml](openapi/schama/schema.yaml)</ul> | 🟢     |
