# Product Overview

PHP FHIRTools is a comprehensive toolkit for working with FHIR (Fast Healthcare Interoperability Resources) in PHP applications. It provides modular components for generating PHP model classes from FHIR StructureDefinitions and serializing/deserializing FHIR resources to/from JSON and XML formats.

## Core Functionality

- **FHIR Code Generation**: Automatically generates PHP model classes from FHIR StructureDefinitions for R4, R4B, and R5 versions
- **FHIR Serialization**: Comprehensive serialization system with JSON/XML support, validation modes, and extension handling
- **FHIRPath Support**: Implementation of FHIRPath expression language for querying FHIR resources
- **Symfony Integration**: Full Symfony Bundle integration with dependency injection and console commands

## Multi-Component Architecture

The project is organized as a monorepo with separate, independently usable components:

- **FHIRBundle**: Symfony Bundle for seamless framework integration
- **CodeGeneration Component**: Standalone FHIR class generator
- **Serialization Component**: Standalone FHIR JSON/XML serialization
- **FHIRPath Component**: FHIRPath expression evaluation engine
- **Models Component**: Generated FHIR model classes for different versions

## Target Users

- Healthcare software developers working with FHIR
- PHP developers building healthcare interoperability solutions
- Organizations implementing FHIR-based APIs and systems
- Developers needing type-safe FHIR resource handling in PHP

## Key Benefits

- Type-safe PHP classes for all FHIR resources
- Standards-compliant serialization following FHIR specifications
- Modular design allowing standalone or integrated usage
- Support for multiple FHIR versions (R4, R4B, R5)
- Comprehensive validation and error handling
