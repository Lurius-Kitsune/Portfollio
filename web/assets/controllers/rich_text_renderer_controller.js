import { Controller } from "@hotwired/stimulus";
import { generateHTML } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import { TextStyle } from "@tiptap/extension-text-style";
import { Color } from "@tiptap/extension-color";

export default class extends Controller {
    static targets = ["content"];

    static values = {
        content: String,
    };

    connect() {
        this.render();
    }

    render() {
        try {
            let content = JSON.parse(this.contentValue);

            /*
             * Compatibilité avec ton ancien format :
             *
             * [
             *   "Premier paragraphe",
             *   "Deuxième paragraphe"
             * ]
             */
            if (Array.isArray(content)) {
                content = {
                    type: "doc",
                    content: content
                        .filter(
                            (text) =>
                                typeof text === "string" &&
                                text.trim() !== "",
                        )
                        .map((text) => ({
                            type: "paragraph",
                            content: [
                                {
                                    type: "text",
                                    text,
                                },
                            ],
                        })),
                };
            }

            if (
                !content ||
                typeof content !== "object" ||
                content.type !== "doc"
            ) {
                throw new Error("Format TipTap invalide.");
            }

            const html = generateHTML(content, [
                StarterKit.configure({
                    heading: {
                        levels: [2, 3, 4],
                    },
                }),
                TextStyle,
                Color.configure({
                    types: ["textStyle"],
                }),
            ]);

            this.contentTarget.innerHTML = html;
        } catch (error) {
            console.error(
                "Impossible de rendre le contenu TipTap :",
                error,
            );

            this.contentTarget.innerHTML = "";
        }
    }
}