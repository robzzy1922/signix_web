# Command Pattern Implementation for Document Management

## Overview

This project implements the Command Pattern for document management operations including uploading documents, generating QR codes, saving barcode positions, and viewing documents.

## Command Pattern Structure

1. **Command Interface**: This defines a common interface for all concrete command classes.

    - `app/Commands/Command.php` - Defines the `execute()` method that all commands must implement.

2. **Concrete Commands**: These implement the Command interface and encapsulate specific operations.

    - `app/Commands/UploadDocCommand.php` - Handles document upload
    - `app/Commands/GenerateQRCommand.php` - Generates QR code for a document
    - `app/Commands/SaveBarcodeCommand.php` - Saves barcode position in a document
    - `app/Commands/ViewDocCommand.php` - Prepares a document for viewing

3. **Command Invoker**: Responsible for executing commands.

    - `app/Commands/CommandInvoker.php` - Holds a command and executes it when requested

4. **Receiver**: Contains the actual implementation of operations.

    - `app/Repositories/DocumentRepository.php` - Provides methods to interact with documents

5. **Client**: Creates and configures concrete commands.
    - `app/Services/DocumentService.php` - Creates commands and sets them on the invoker
    - `app/Http/Controllers/DocumentController.php` - Uses the DocumentService

## Benefits of This Implementation

-   **Decoupling**: The Command Pattern decouples the object that invokes the operation from the object that performs the operation.
-   **Extensibility**: New document operations can be added by creating new command classes without modifying existing code.
-   **Testability**: Commands can be tested independently of controllers or repositories.
-   **Flexibility**: Commands can be stored, passed around, and executed at different times.

## Usage Examples

1. **Uploading a Document**:

```php
$documentService->upload($request->file('document'));
```

2. **Generating a QR Code**:

```php
$documentService->generateQR($dokumen);
```

3. **Saving Barcode Position**:

```php
$documentService->saveBarcodePos($dokumen, $x, $y, $width, $height);
```

4. **Viewing a Document**:

```php
$documentService->view($dokumen);
```

## Sequence Flow

1. The controller receives a request and calls the appropriate DocumentService method
2. The DocumentService creates a concrete command with the necessary parameters
3. The DocumentService sets the command on the CommandInvoker
4. The CommandInvoker executes the command
5. The command performs its operation, typically using the DocumentRepository
6. The result is returned back through the call chain to the controller
