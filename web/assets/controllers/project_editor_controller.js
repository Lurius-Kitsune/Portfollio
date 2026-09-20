import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = [
        "display",
        "editor",

        "role",
        "roleDisplay",

        "intro",
        "introDisplay",

        "conclusionTitle",
        "conclusionTitleDisplay",

        "conclusionText",
        "conclusionContentDisplay",

        "saveButton",

        "contentBlock",
        "contentDisplay",
        "contentEditor",
        "contentTheme",
        "contentThemeDisplay",
        "contentTitle",
        "contentTitleDisplay",
        "contentText",
        "contentTextDisplay",

        "contentsContainer",
        "createContentEditor",
        "newContentTheme",
        "newContentTitle",
        "createContentButton",
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
    async saveContent(event) {
        const button = event.currentTarget;

        const block = button.closest(
            '[data-project-editor-target="contentBlock"]',
        );

        const contentId = button.dataset.contentId;

        const theme = block.querySelector(
            '[data-project-editor-target="contentTheme"]',
        );

        const title = block.querySelector(
            '[data-project-editor-target="contentTitle"]',
        );

        const editorElement = block.querySelector(
            '[data-rich-text-editor-target="editor"]',
        );

        const editorContainer = editorElement.closest(
            '[data-controller~="rich-text-editor"]',
        );

        const editorController =
            this.application.getControllerForElementAndIdentifier(
                editorContainer,
                "rich-text-editor",
            );

        const content = editorController.getJSON();

        button.disabled = true;
        button.textContent = "Enregistrement...";

        try {
            const url = this.contentUpdateUrl(contentId);

            const response = await fetch(url, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({
                    themeName: theme.value,
                    title: title.value,
                    content: content,
                }),
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message ?? "Une erreur est survenue.");
            }

            // Pour l'instant, on recharge la page.
            // Le rendu public du JSON TipTap sera à adapter ensuite.
            location.reload();
        } catch (error) {
            console.error(error);
            alert(error.message);
        } finally {
            button.disabled = false;
            button.textContent = "Enregistrer";
        }
    }

    contentUpdateUrl(contentId) {
        return `/project/${this.projectSlugValue}/content/${contentId}`;
    }

    async save() {
        this.saveButtonTarget.disabled = true;
        this.saveButtonTarget.textContent = "Enregistrement...";

        const editorController =
            this.application.getControllerForElementAndIdentifier(
                this.conclusionTextTarget,
                "rich-text-editor",
            );

        const data = {
            role: this.roleTarget.value,
            intro: this.introTarget.value,
            conclusionTitle: this.conclusionTitleTarget.value,
            conclusionContent: editorController.getJSON(),
        };

        try {
            const response = await fetch(this.updateUrlValue, {
                method: "PATCH",

                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },

                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message ?? "Une erreur est survenue.");
            }

            // Mise à jour de l'affichage
            this.roleDisplayTarget.textContent = result.project.role;
            this.introDisplayTarget.textContent = result.project.intro;

            this.conclusionTitleDisplayTarget.textContent =
                result.project.conclusionTitle;

            // On reconstruit la conclusion
            this.conclusionContentDisplayTarget.innerHTML = "";
            location.reload();
        } catch (error) {
            console.error(error);

            alert(error.message);
        } finally {
            this.saveButtonTarget.disabled = false;
            this.saveButtonTarget.textContent = "Enregistrer";
        }
    }

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

    openCreateContent() {
        this.createContentEditorTarget.classList.remove("hidden");

        this.newContentThemeTarget.focus();
    }

    cancelCreateContent() {
        this.createContentEditorTarget.classList.add("hidden");

        this.newContentThemeTarget.value = "";
        this.newContentTitleTarget.value = "";

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

    async createContent() {
        const button = this.createContentButtonTarget;

        const themeName = this.newContentThemeTarget.value.trim();
        const title = this.newContentTitleTarget.value.trim();

        const editorElement = this.createContentEditorTarget.querySelector(
            '[data-rich-text-editor-target="editor"]',
        );

        const editorContainer = editorElement.closest(
            '[data-controller~="rich-text-editor"]',
        );

        const editorController =
            this.application.getControllerForElementAndIdentifier(
                editorContainer,
                "rich-text-editor",
            );

        const content = editorController.getJSON();

        if (!title) {
            alert("Le titre est obligatoire.");
            return;
        }

        button.disabled = true;
        button.textContent = "Création...";

        try {
            const response = await fetch(this.createContentUrlValue, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "text/html",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: JSON.stringify({
                    themeName,
                    title,
                    content,
                }),
            });

            if (!response.ok) {
                const message = await response.text();
                throw new Error(message);
            }

            const html = await response.text();

            this.contentsContainerTarget.insertAdjacentHTML("beforeend", html);

            this.cancelCreateContent();
        } catch (error) {
            console.error(error);
            alert(error.message);
        } finally {
            button.disabled = false;
            button.textContent = "Créer la section";
        }
    }
}
