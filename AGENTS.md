# AGENTS.md - import-product-media

## Zweck & Verantwortung

Das `import-product-media` Modul bietet **Product Media Gallery Import-Funktionalität** für Bilder und Video-Verwaltung. Es ist ein **Tier 5 Modul** in der Import-Architektur und erweitert das `import-product` Modul mit spezialisierten Funktionen für Mediendateien.

**Hauptverantwortung:**
- Product Media Gallery Import (Bilder, Videos, PDFs)
- Gallery Value Import (Attribute wie Label, Position)
- Media-to-Entity Mapping und Assoziierung
- Repository Pattern Implementation für persistente Speicherung
- Service Layer für Media-spezifische Business Logic
- Observer Pattern für Hook-Integration in der Import-Pipeline
- Datei-Download und -Validierung

**Modul-Kategorie:** Integration/Extension Module  
**Komplexität:** ⭐⭐⭐ (Mittel-Hoch - Datei-Handling)

## Architektur & Design Patterns

### Kern-Klassen
- **ProductMediaRepository**: Persistiert Mediendateien und Galerie-Einträge
- **ProductMediaValueRepository**: Verwaltet Gallery Value Attribute (Label, Position, Role)
- **MediaProcessor**: Service Layer für Media-Verarbeitung und Download
- **MediaObserver**: Observer für Media Lifecycle Hooks
- **MediaDownloadManager**: Koordiniert Datei-Download und Speicherung
- **MediaValidationService**: Validiert Media-Format und Größe

### Verwendete Patterns
- **Observer Pattern**: Zur Einklinken in Import-Lifecycle Events
- **Repository Pattern**: Für abstrakte Datenschicht
- **Service Layer Pattern**: Geschäftslogik isoliert von Repositories
- **Factory Pattern**: Für Media-Objekt-Erstellung
- **File Handler Pattern**: Für Datei-Download und -Speicherung

### Datenfluss
```
Media CSV/JSON (mit Image URLs)
    ↓
Parser (import-serializer)
    ↓
Converter (import-converter)
    ↓
Media Processor
    ├─→ MediaDownloadManager (Download Images)
    ├─→ ProductMediaRepository (Store in Galerie)
    └─→ ProductMediaValueRepository (Store Attributes)
    ↓
Magento Database + File System
    ├─→ catalog_product_entity_media_gallery
    └─→ pub/media/catalog/product/*
```

## Abhängigkeiten

### Externe Pakete
- **Keine direkten PHP-Pakete**

### TechDivision Dependencies
- **import-product** ^26.2 - Base Product Importer (Parent)
- **import-converter** - Data Conversion Framework
- **import-serializer** - Data Serialization

### Abhängig von diesem Modul (1 Reverse Dependency)
- **import-product-media-ee** - EE-spezifische Media Extensions

## Wichtige Entry Points

### Repository Klassen
```php
// Product Media Repository - Galerie-Einträge
ProductMediaRepository::create($row): void
ProductMediaRepository::findByProductId($productId): array

// Product Media Value Repository - Attribute
ProductMediaValueRepository::create($row): void
ProductMediaValueRepository::findByMediaId($mediaId): array
```

### Service Methods
- `MediaProcessor::process()` - Haupteingangspunkt
- `MediaDownloadManager::downloadImage()` - Download externe Image
- `MediaDownloadManager::saveToFileSystem()` - Speichern lokal
- `MediaValidationService::validate()` - Datei-Validierung

## Events & Extension Points

**Keine Custom Events** - Tier 5 Importer-Modul nutzt Parent-Events aus import-product

### Observer Hooks
- `product.import.media.download.pre` - Vor Image-Download
- `product.import.media.process.post` - Nach Media-Verarbeitung
- `product.import.media.persist.error` - Bei Fehler
- `product.import.media.validation.failed` - Validierungs-Fehler

## Database Schema

### Relevante Tabellen
- **catalog_product_entity_media_gallery** - Media Galerie Einträge
  - `attribute_id` - Media Gallery Attribut (80)
  - `entity_id` - Product ID
  - `value` - Image Filename (z.B. /m/e/media.jpg)
  - `media_type` - image | external-video

- **catalog_product_entity_media_gallery_value** - Attribute pro Store
  - `value_id` - Link zu media_gallery
  - `store_id` - Store
  - `label` - Image Label/Caption
  - `position` - Reihenfolge
  - `disabled` - 0/1

- **catalog_product_entity_media_gallery_value_video** - Video Metadata
  - `value_id`
  - `video_provider_id` - youtube|vimeo
  - `video_url`, `video_title`, `video_description`
  - `video_metadata`

## Common Use Cases

### Use Case 1: Product Images Import
```php
// CSV Dateistruktur:
// sku,image_url,image_label,image_position,image_role

// PROD-001,https://example.com/images/prod1.jpg,Main Image,1,image
// PROD-001,https://example.com/images/prod1-alt.jpg,Alt View,2,small_image
// Verarbeitung:
// 1. Download beide Images von URL
// 2. Speichere in pub/media/catalog/product/
// 3. Erstelle Gallery-Einträge
// 4. Setze Labels und Positionen
```

### Use Case 2: Video Import mit Metadata
```php
// CSV mit Video:
// sku,video_url,video_provider,video_title

// PROD-002,https://youtube.com/watch?v=abc123,youtube,Product Video
// Verarbeitung:
// 1. Extrahiere Video-Informationen
// 2. Speichern Video-Metadata
// 3. Erstelle externe Video-Gallery
```

## Performance Considerations

### Wichtige Performance-Aspekte
1. **Datei-Downloads**: Jedes Bild muss heruntergeladen werden (IO-intensiv)
2. **Datei-Speicherung**: Schreiben auf Dateisystem + Datenbank
3. **Image-Resizing**: Optional Thumbnail-Generierung
4. **Timeout-Handling**: Remote URLs können ausfallen

### Optimierungen
- Batch-Processing für Images (max 10-20 pro Batch wegen Download)
- Nutze Parallel-Downloads für schnellere Verarbeitung
- Cache Download-URLs um Duplikate zu vermeiden
- Nutze lokale File-Speicherung statt S3 während Import

### Speicher-Optimierung
- Streame große Image-Downloads (nicht vollständig in RAM)
- Cleanup Temp-Dateien nach Speicherung
- Nutze Stream-basierte Datei-Handlung
- Limitiere Bild-Größen (max 10MB pro Image)

## Hints für KI-Agenten

### Kritisches Verständnis
1. **Tier 5 Modul**: Spezialisierte Extension des Product Importers
2. **Media-fokussiert**: Arbeitet mit Bilder und Video-Dateien
3. **Datei-Handling**: IO-intensiv mit Remote-Downloads
4. **Observer Pattern**: Integration mit Import-Pipeline durch Hooks
5. **Dual-Persistierung**: Datenbank + Dateisystem

### Häufige Fehler
- ❌ Keine Validierung von Image-URLs
- ❌ Keine Fehlerbehandlung für fehlende/ungültige Images
- ❌ Keine Timeout-Handhabung für Downloads
- ❌ Nicht prüfen ob Image bereits existiert
- ❌ Label/Position nicht korrekt setzen
- ❌ Filesize-Limits nicht beachten

### Best Practices
- ✅ Validiere Image-URLs vor Download
- ✅ Implementiere Retry-Logic für fehlgeschlagene Downloads
- ✅ Dedupliziere Images (gleiche URL = gleiche Datei)
- ✅ Setze sinnvolle Timeout-Werte
- ✅ Nutze Transaktionen für Konsistenz
- ✅ Teste mit echten Image-URLs

## Known Limitations

- **Remote Downloads**: Abhängig von externe Verfügbarkeit
- **Datei-Format**: Nur JPEG, PNG, GIF, WebP unterstützt
- **Größe-Limits**: Max 10MB pro Image (konfigurierbar)
- **Timeout**: Downloads können timeout (default 30 Sekunden)
- **Video-Limitiert**: Nur YouTube/Vimeo Video-Embedding
- **Keine Batch-Compression**: Bilder werden nicht komprimiert

## Related Modules

### Direct Dependencies
- **import-product** - Base Product Importer (Parent)
- **import-converter** - Data Conversion Framework

### Related/Companion Modules
- **import-product-media-ee** - EE-spezifische Media Extensions
- **import-product** - Base Product Importer
- **import-serializer** - Data Serialization

## Troubleshooting

### Problem: Images werden nicht heruntergeladen
**Mögliche Ursachen:**
1. Image-URL ungültig
2. Remote Server antwortet nicht
3. Netzwerk-Timeout
4. Firewall blockiert Download

**Lösung:**
- Validiere dass URLs erreichbar sind
- Prüfe Firewall/Proxy-Settings
- Erhöhe Timeout-Wert
- Prüfe Logs auf Fehler

### Problem: Images werden hochgeladen aber nicht in Galerie angezeigt
**Mögliche Ursachen:**
1. Gallery Value nicht gespeichert
2. Label/Position nicht korrekt
3. Image-Attribut nicht erkannt

**Lösung:**
- Validiere dass catalog_product_entity_media_gallery_value gefüllt ist
- Prüfe dass Position > 0 ist
- Stelle sicher dass attribute_id = 80 ist

### Problem: Datei-Permissions Fehler
**Mögliche Ursachen:**
1. pub/media Verzeichnis nicht beschreibbar
2. File-Owner ist falsch

**Lösung:**
- Prüfe dass pub/media 755 Permissions hat
- Stelle sicher dass Web-Server User schreiben kann

## Zusammenfassung

`import-product-media` ist ein **Tier 5 Importer-Modul**, das spezialisierte Product Media Gallery Import-Funktionalität mit Remote-Download und Datei-Management bereitstellt. Es koordiniert Herunterladen, Speicherung und Datenbank-Persistierung von Produktbildern und Videos.

**Für KI-Agenten:** Verstehe dieses Modul als:
- **Product Media Importer** mit Remote-Download Support
- **Tier 5 Integration** in die generische Import-Pipeline
- **Datei-fokussiert** mit IO-Handling
- **Dual-System** mit Datenbank + Dateisystem Persistierung
