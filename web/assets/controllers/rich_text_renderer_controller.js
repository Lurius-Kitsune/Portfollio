import { Controller } from "@hotwired/stimulus";
import { generateHTML } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";

import Underline from "@tiptap/extension-underline";
import { TextStyle } from "@tiptap/extension-text-style";
import { Color } from "@tiptap/extension-color";
import Link from "@tiptap/extension-link";
import TextAlign from "@tiptap/extension-text-align";

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
                                typeof text === "string" && text.trim() !== "",
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
                        levels: [1, 2, 3, 4, 5, 6],
                    },
                }),

                Underline,

                TextStyle,

                Color.configure({
                    types: ["textStyle"],
                }),

                Link.configure({
                    openOnClick: true,
                }),

                TextAlign.configure({
                    types: ["heading", "paragraph"],
                }),
            ]);

            this.contentTarget.innerHTML = html;
        } catch (error) {
            console.error("Impossible de rendre le contenu TipTap :", error);

            this.contentTarget.innerHTML = "";
        }
    }
}
