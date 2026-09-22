import { Controller } from "@hotwired/stimulus";
import { Editor } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";
import { TextStyle } from "@tiptap/extension-text-style";
import { Color } from "@tiptap/extension-color";

export default class extends Controller {
    static targets = [
        "editor",
        "toolbar",
        "boldButton",
        "italicButton",
        "underlineButton",
        "paragraphButton",
        "headingButton",
        "bulletListButton",
        "orderedListButton",
        "color",
    ];

    static values = {
        content: String,
    };

    connect() {
        this.tiptap = new Editor({
            element: this.editorTarget,

            extensions: [
                StarterKit.configure({
                    heading: {
                        levels: [2, 3, 4],
                    },
                }),

                TextStyle,

                Color.configure({
                    types: ["textStyle"],
                }),
            ],

            content: this.getInitialContent(),

            onUpdate: () => {
                this.updateToolbar();
            },

            onSelectionUpdate: () => {
                this.updateToolbar();
            },
        });

        this.updateToolbar();
    }

    getInitialContent() {
        if (!this.contentValue) {
            return {
                type: "doc",
                content: [
                    {
                        type: "paragraph",
                    },
                ],
            };
        }

        try {
            const content = JSON.parse(this.contentValue);

            // Ancien format :
            // ["texte 1", "texte 2"]
            if (Array.isArray(content)) {
                return {
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

            // Format TipTap
            if (
                content &&
                typeof content === "object" &&
                content.type === "doc"
            ) {
                return content;
            }

            return null;
        } catch (error) {
            console.error("Impossible de parser le contenu :", error);

            return null;
        }
    }

    // -------------------------
    // Toolbar
    // -------------------------

    toggleBold() {
        this.tiptap.chain().focus().toggleBold().run();
        this.updateToolbar();
    }

    toggleItalic() {
        this.tiptap.chain().focus().toggleItalic().run();
        this.updateToolbar();
    }

    toggleUnderline() {
        this.tiptap.chain().focus().toggleUnderline().run();
        this.updateToolbar();
    }

    setParagraph() {
        this.tiptap.chain().focus().setParagraph().run();
        this.updateToolbar();
    }

    setHeading() {
        this.tiptap.chain().focus().toggleHeading({ level: 2 }).run();

        this.updateToolbar();
    }

    toggleBulletList() {
        this.tiptap.chain().focus().toggleBulletList().run();

        this.updateToolbar();
    }

    toggleOrderedList() {
        this.tiptap.chain().focus().toggleOrderedList().run();

        this.updateToolbar();
    }

    setColor(event) {
        const color = event.currentTarget.value;

        this.tiptap.chain().focus().setColor(color).run();

        this.updateToolbar();
    }

    removeColor() {
        this.tiptap.chain().focus().unsetColor().run();

        this.updateToolbar();
    }

    // -------------------------
    // État des boutons
    // -------------------------

    updateToolbar() {
        if (!this.tiptap) {
            return;
        }

        this.setButtonState(
            this.boldButtonTarget,
            this.tiptap.isActive("bold"),
        );

        this.setButtonState(
            this.italicButtonTarget,
            this.tiptap.isActive("italic"),
        );

        this.setButtonState(
            this.underlineButtonTarget,
            this.tiptap.isActive("underline"),
        );

        this.setButtonState(
            this.paragraphButtonTarget,
            this.tiptap.isActive("paragraph"),
        );

        this.setButtonState(
            this.headingButtonTarget,
            this.tiptap.isActive("heading", {
                level: 2,
            }),
        );

        this.setButtonState(
            this.bulletListButtonTarget,
            this.tiptap.isActive("bulletList"),
        );

        this.setButtonState(
            this.orderedListButtonTarget,
            this.tiptap.isActive("orderedList"),
        );
    }

    setButtonState(button, active) {
        button.classList.toggle("bg-cyan-500", active);
        button.classList.toggle("text-slate-950", active);

        button.classList.toggle("bg-gray-800", !active);
        button.classList.toggle("text-gray-300", !active);
    }

    // -------------------------
    // JSON
    // -------------------------

    getJSON() {
        return this.tiptap.getJSON();
    }

    disconnect() {
        if (this.tiptap) {
            this.tiptap.destroy();
        }
    }
}
