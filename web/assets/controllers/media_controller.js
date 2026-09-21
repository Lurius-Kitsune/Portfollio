import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = [
        "display",
        "editor",
        "newContentTheme",
        "contentsContainer",
        "createContentEditor",
    ];

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
