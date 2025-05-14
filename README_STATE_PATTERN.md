# State Pattern Implementation for Document Management

## Overview

This project implements the State Pattern for document management, allowing documents to transition through different states like "Pending", "Approved", and "Revision".

## State Pattern Structure

### Class Diagram

```
+----------------+       +-------------------+
| Dokumen        |<>---->| DocumentState    |
+----------------+       +-------------------+
| - state        |       | + handle()        |
| - status       |       | + getStatus()     |
| + setState()   |       +-------------------+
| + getState()   |              ^
| + handle()     |              |
+----------------+      +-------+-------+-------+-------+
                        |               |               |
            +-----------------+ +----------------+ +----------------+
            | PendingState    | | ApprovedState  | | RevisionState  |
            +-----------------+ +----------------+ +----------------+
            | + handle()      | | + handle()     | | + handle()     |
            | + getStatus()   | | + getStatus()  | | + getStatus()  |
            +-----------------+ +----------------+ +----------------+
```

## Implementation Components

1. **Context Class**:

    - `app/Models/Dokumen.php` - This class maintains a reference to a state object and delegates state-specific behavior to it.

2. **State Interface**:

    - `app/States/DocumentState.php` - This interface defines methods that all concrete state classes must implement.

3. **Concrete State Classes**:

    - `app/States/PendingState.php` - Implements behavior for when document is in 'diajukan' state
    - `app/States/ApprovedState.php` - Implements behavior for when document is in 'disahkan' state
    - `app/States/RevisionState.php` - Implements behavior for when document is in 'direvisi' state

4. **Service Class**:
    - `app/Services/DocumentStateService.php` - Provides helper methods to manage state transitions and process actions

## Document State Flow

1. **Initial State**: When a document is first uploaded, it's in the `PendingState` (status: 'diajukan')
2. **Review Process**: A reviewer can either:
    - Approve the document → transitions to `ApprovedState` (status: 'disahkan')
    - Request revisions → transitions to `RevisionState` (status: 'direvisi')
3. **Revision Process**: When document is in revision state:
    - Author can resubmit → transitions back to `PendingState` (status: 'diajukan')

## Benefits of State Pattern

-   **Organized Code**: State-specific behavior is encapsulated in separate classes.
-   **Open/Closed Principle**: New states can be added without changing existing code.
-   **Simplified State Transitions**: Transitions are handled within the state classes.
-   **Reduced Conditional Logic**: Eliminates complex if/else or switch statements.

## Usage Examples

1. **Document Submission**:

```php
// Document starts in pending state
$dokumen->handle();  // Initialize as pending
```

2. **Document Approval**:

```php
// Transition to approved state
$dokumen->handle(['verified' => true, 'keterangan' => 'Dokumen telah diverifikasi']);
```

3. **Requesting Revision**:

```php
// Transition to revision state
$dokumen->handle([
    'needs_revision' => true,
    'keterangan_revisi' => 'Perbaiki format surat'
]);
```

4. **Resubmitting After Revision**:

```php
// Transition back to pending state
$dokumen->handle([
    'resubmitted' => true,
    'keterangan_pengirim' => 'Sudah diperbaiki sesuai permintaan'
]);
```

5. **Using the State Service**:

```php
$stateService->processAction($dokumen, 'approve', ['keterangan' => 'Approved by administrator']);
```
