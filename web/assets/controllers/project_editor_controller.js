import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = [
        "display",
        "editor",
        "newContentTheme",
        "contentsContainer",
        "createContentEditor",
    ];

    static values = {
        updateUrl: String,
        createContentUrl: String,
        projectSlug: String,
    };

    edit() {
        this.displayTarget.classList.add("hidden");
        this.editorTarget.classList.remove("hidden");
    }

    cancel() {
        this.editorTarget.classList.add("hidden");
        this.displayTarget.classList.remove("hidden");
    }

    /*
     * ---------------------------------------------------------
     * PROJECT
     * ---------------------------------------------------------
     */

    save(event) {
        const form = event.currentTarget;

        const button = form.querySelector('button[type="submit"]');

        if (button) {
            button.disabled = true;
            button.textContent = "Enregistrement...";
        }

        this.formEvent(event);
    }

    /*
     * ---------------------------------------------------------
     * PROJECT CONTENT
     * ---------------------------------------------------------
     */

    editContent(event) {
        const block = event.currentTarget.closest(
            '[data-project-editor-target="contentBlock"]',
        );

        const display = block.querySelector(
            '[data-project-editor-target="contentDisplay"]',
        );

        const editor = block.querySelector(
            '[data-project-editor-target="contentEditor"]',
        );

        display.classList.add("hidden");
        editor.classList.remove("hidden");
    }

    cancelContent(event) {
        const block = event.currentTarget.closest(
            '[data-project-editor-target="contentBlock"]',
        );

        const display = block.querySelector(
            '[data-project-editor-target="contentDisplay"]',
        );

        const editor = block.querySelector(
            '[data-project-editor-target="contentEditor"]',
        );

        editor.classList.add("hidden");
        display.classList.remove("hidden");
    }

    saveContent(event) {
        const form = event.currentTarget;

        const button = form.querySelector('button[type="submit"]');

        if (button) {
            button.disabled = true;
            button.textContent = "Enregistrement...";
        }

        this.formEvent(event);
    }

    contentUpdateUrl(contentId) {
        return `/project/${this.projectSlugValue}/content/${contentId}`;
    }

    /*
     * ---------------------------------------------------------
     * DELETE CONTENT
     * ---------------------------------------------------------
     */

    async deleteContent(event) {
        const button = event.currentTarget;

        const block = button.closest(
            '[data-project-editor-target="contentBlock"]',
        );

        const contentId = block.dataset.contentId;

        if (!confirm("Voulez-vous vraiment supprimer cette section ?")) {
            return;
        }

        button.disabled = true;

        try {
            const response = await fetch(
                `/project/${this.projectSlugValue}/content/${contentId}`,
                {
                    method: "DELETE",
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                },
            );

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(
                    result.message ?? "Impossible de supprimer cette section.",
                );
            }

            block.remove();
        } catch (error) {
            console.error(error);
            alert(error.message);

            button.disabled = false;
        }
    }

    /*
     * ---------------------------------------------------------
     * CREATE CONTENT
     * ---------------------------------------------------------
     */

    openCreateContent() {
        this.createContentEditorTarget.classList.remove("hidden");
        this.newContentThemeTarget.focus();
    }

    cancelCreateContent() {
        this.createContentEditorTarget.classList.add("hidden");

        const editorElement = this.createContentEditorTarget.querySelector(
            '[data-rich-text-editor-target="editor"]',
        );

        const editorContainer = editorElement?.closest(
            '[data-controller~="rich-text-editor"]',
        );

        const editorController = editorContainer
            ? this.application.getControllerForElementAndIdentifier(
                  editorContainer,
                  "rich-text-editor",
              )
            : null;

        if (editorController) {
            editorController.tiptap.commands.clearContent();
        }
    }

    createContent(event) {
        const form = event.currentTarget;

        const button = form.querySelector('button[type="submit"]');

        if (button) {
            button.disabled = true;
            button.textContent = "Création...";
        }

        this.formEvent(event);
    }

    async formEvent(event) {
        event.preventDefault();
        const form = event.currentTarget;

        const editorElement = form.querySelector(
            '[data-rich-text-editor-target="editor"]',
        );

        const editorContainer = editorElement?.closest(
            '[data-controller~="rich-text-editor"]',
        );

        const editorController = editorContainer
            ? this.application.getControllerForElementAndIdentifier(
                  editorContainer,
                  "rich-text-editor",
              )
            : null;

        if (!editorController) {
            alert("L'éditeur de contenu est introuvable.");
            return;
        }

        const contentField = form.querySelector(
            '[data-project-editor-target="content"]',
        );

        if (contentField) {
            contentField.value = JSON.stringify(editorController.getJSON());
        }

        try {
            const response = await fetch(form.action, {
                method: form.method || "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: new FormData(form),
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message ?? "Une erreur est survenue.");
            }

            window.location.href = result.redirectUrl;
        } catch (error) {
            console.error(error);
            alert(error.message);
        } finally {
            form.disabled = false;
            form.textContent = "Enregistrer";
        }
    }
}