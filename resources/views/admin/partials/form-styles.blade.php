<style>
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--border-color);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .required {
        color: var(--primary-orange);
    }

    .form-input, .form-select, .form-textarea {
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: var(--text-primary);
        font-size: 0.875rem;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--primary-orange);
        box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.1);
    }

    .form-input::placeholder, .form-textarea::placeholder {
        color: var(--text-secondary);
    }

    .form-textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem;
        background: var(--bg-dark);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .form-checkbox:hover {
        border-color: var(--primary-orange);
    }

    .form-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .form-help {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .form-error {
        font-size: 0.75rem;
        color: #ef4444;
        margin-top: 0.25rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }

    .btn-primary {
        background: var(--primary-orange);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9375rem;
    }

    .btn-primary:hover {
        background: var(--primary-orange-dark);
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: var(--bg-dark);
        color: var(--text-primary);
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        border: 1px solid var(--border-color);
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: var(--border-color);
    }

    .image-upload-preview {
        width: 200px;
        height: 200px;
        border-radius: 10px;
        border: 2px dashed var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: var(--bg-dark);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .image-upload-preview:hover {
        border-color: var(--primary-orange);
    }

    .image-upload-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            justify-content: center;
        }
    }
</style>

