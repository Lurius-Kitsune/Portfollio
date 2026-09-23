import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = [
        "display",
        "editor",
        "newContentTheme",
        "contentsContainer",
        "createContentEditor",
        // --- ajoutés pour la gestion des médias ---
        "mediaForm",
        "fileField",
        "urlField",
        "fileModeButton",
        "urlModeButton",
        "mediaErrorBanner",
        "mediaSubmitButton",
        "mediaGrid",

        "mediaContainer",
    ];

    connect() {
        if (this.hasFileModeButtonTarget) {
            this.showFileMode();
        }
    }

    /* ===== Toggle Fichier / URL ===== */

    showFileMode() {
        this.fileFieldTarget.classList.remove("hidden");
        this.urlFieldTarget.classList.add("hidden");
        this.#activateButton(
            this.fileModeButtonTarget,
            this.urlModeButtonTarget,
        );
    }

    showUrlMode() {
        this.urlFieldTarget.classList.remove("hidden");
        this.fileFieldTarget.classList.add("hidden");
        this.#activateButton(
            this.urlModeButtonTarget,
            this.fileModeButtonTarget,
        );
    }

    #activateButton(active, inactive) {
        active.classList.add(
            "bg-white",
            "text-cyan-600",
            "shadow-sm",
            "dark:bg-gray-800",
            "dark:text-cyan-400",
        );
        active.classList.remove("text-gray-500");

        inactive.classList.remove(
            "bg-white",
            "text-cyan-600",
            "shadow-sm",
            "dark:bg-gray-800",
            "dark:text-cyan-400",
        );
        inactive.classList.add("text-gray-500");
    }

    /* ===== Ajout d'un média ===== */

    /**
     * @param {SubmitEvent} event
     * @returns {Promise<void>}
     */
    async submitMedia(event) {
        event.preventDefault();

        this.#hideMediaError();
        this.mediaSubmitButtonTarget.disabled = true;

        try {
            const formData = new FormData(this.mediaFormTarget);

            const response = await fetch(this.mediaFormTarget.action, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                body: formData,
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                this.#showMediaError(
                    result.messages ?? ["Impossible d'ajouter ce média."],
                );
                return;
            }

            this.mediaFormTarget.reset();
            this.showFileMode();

            this.mediaContainerTarget.insertAdjacentHTML(
                "beforeend",
                result.html,
            );

            this.dispatch("mediaAdded", {
                detail: {
                    media: result.media,
                },
            });
        } catch (error) {
            console.error(error);

            this.#showMediaError([
                "Une erreur réseau est survenue. Veuillez réessayer.",
            ]);
        } finally {
            this.mediaSubmitButtonTarget.disabled = false;
        }
    }

    /**
     * @param {string[]} messages
     * @returns {void}
     */
    #showMediaError(messages) {
        const banner = this.mediaErrorBannerTarget;
        const container = banner.querySelector("#errorTextBanner");

        container.replaceChildren();

        messages.forEach((error) => {
            const div = document.createElement("div");
            div.textContent = error;
            container.appendChild(div);
        });

        banner.classList.remove("hidden");
        banner.classList.add("flex");
    }

    #hideMediaError() {
        this.mediaErrorBannerTarget.classList.add("hidden");
        this.mediaErrorBannerTarget.classList.remove("flex");
    }

    /* ===== Suppression d'un média ===== */

    async deleteMedia(event) {
        const button = event.currentTarget;
        const id = event.params.id;
        const projectId = event.params.projectid;
        const card = button.closest('[data-controller="media-item"]');

        if (!confirm("Voulez-vous vraiment supprimer cette section ?")) {
            return;
        }

        button.disabled = true;

        try {
            const response = await fetch(`/project/${projectId}/media/${id}`, {
                method: "DELETE",
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(
                    result.message ?? "Impossible de supprimer cette section.",
                );
            }
            card?.remove();
        } catch (error) {
            console.error(error);
            alert(error.message);

            button.disabled = false;
        }
    }
}