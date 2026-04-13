# AGENTS.md - import-product-media

## Zweck & Verantwortung

Das `import-product-media` Modul bietet **Product Media Gallery Import-Funktionalität**. Es ist ein **Tier 5 Modul** und erweitert `import-product`.

**Hauptverantwortung:**
- Product Media Gallery Import
- Gallery Value Import
- Gallery-to-Entity Mapping
- Repository Pattern für Media-Daten
- Service Layer für Media-Verarbeitung
- Observer Pattern für Media-Hooks

## Architektur & Design Patterns

### Kern-Klassen
- **ProductMediaRepository**: Persistierung von Media Gallery
- **ProductMediaValueRepository**: Persistierung von Gallery Values
- **ProductMediaProcessor**: Service Layer
- **ProductMediaObserver**: Observer für Hooks

### Verwendete Patterns
- **Observer Pattern**: Für Media-Hooks
- **Repository Pattern**: Für Daten-Persistierung
- **Service Layer**: Für Business Logic

## Abhängigkeiten

### Externe Pakete
- **Keine**

### TechDivision Dependencies
- **import-product** ^26.2 - Product Importer

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-product-media-ee** - EE Media Extensions

## Wichtige Entry Points

### Repository Klassen
```php
// Product Media Repository
ProductMediaRepository::create($row): void
ProductMediaRepository::findByProductId($productId): array

// Product Media Value Repository
ProductMediaValueRepository::create($row): void
```

## Events & Extension Points

**Keine Events** - Tier 5 Importer-Modul

## Hints für KI-Agenten

### Wichtig zu verstehen
1. **Tier 5 Modul**: Erweitert Product Importer
2. **Media-fokussiert**: Spezialisiert auf Product Media
3. **Observer Pattern**: Für Hooks
4. **Repository Pattern**: Für Persistierung

## Bekannte Einschränkungen

- **Media-Only**: Keine anderen Features
- **Abhängig von Products**: Erfordert Products zu existieren

## Zusammenfassung

`import-product-media` ist ein **Tier 5 Modul**, das Product Media Gallery Import-Funktionalität bietet. Es erweitert den Product Importer mit spezialisierter Funktionalität für Media Galleries.

**Für Agenten:** Verstehe dieses Modul als **Product Media Importer** mit Observer und Repository Pattern.
