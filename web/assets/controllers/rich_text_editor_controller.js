import { Controller } from "@hotwired/stimulus";
import { Editor } from "@tiptap/core";
import StarterKit from "@tiptap/starter-kit";

import Underline from "@tiptap/extension-underline";
import { TextStyle } from "@tiptap/extension-text-style";
import { Color } from "@tiptap/extension-color";
import Link from "@tiptap/extension-link";
import TextAlign from "@tiptap/extension-text-align";

export default class extends Controller {
    static targets = [
        "editor",
        "toolbar",

        "boldButton",
        "italicButton",
        "underlineButton",
        "codeButton",
        
        "headingButton",

        "bulletListButton",
        "orderedListButton",

        "blockquoteButton",
        "codeBlockButton",

        "linkButton",

        "undoButton",
        "redoButton",

        "color",
        "heading",

        "alignLeftButton",
        "alignCenterButton",
        "alignRightButton",
        "alignJustifyButton",
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
                        levels: [1, 2, 3, 4, 5, 6],
                    },
                }),

                Underline,

                TextStyle,

                Color.configure({
                    types: ["textStyle"],
                }),

                Link.configure({
                    openOnClick: false,
                    autolink: true,
                    linkOnPaste: true,
                }),

                TextAlign.configure({
                    types: ["heading", "paragraph"],
                }),
            ],

            content: this.getInitialContent(),

            onUpdate: () => {
                this.updateToolbar();
            },

            onSelectionUpdate: () => {
                this.updateToolbar();
            },

            onTransaction: () => {
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
            console.error(
                "Impossible de parser le contenu :",
                error,
            );

            return null;
        }
    }

    // =========================================================
    // Texte
    // =========================================================

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

    toggleCode() {
        this.tiptap.chain().focus().toggleCode().run();
        this.updateToolbar();
    }

    setParagraph() {
        this.tiptap.chain().focus().setParagraph().run();
        this.updateToolbar();
    }

    // =========================================================
    // Titres
    // =========================================================

    setHeading(event) {
        const level = parseInt(
            event.currentTarget.value,
            10,
        );

        if (level === 0) {
            this.tiptap
                .chain()
                .focus()
                .setParagraph()
                .run();
        } else {
            this.tiptap
                .chain()
                .focus()
                .toggleHeading({
                    level,
                })
                .run();
        }

        this.updateToolbar();
    }

    // =========================================================
    // Listes
    // =========================================================

    toggleBulletList() {
        this.tiptap
            .chain()
            .focus()
            .toggleBulletList()
            .run();

        this.updateToolbar();
    }

    toggleOrderedList() {
        this.tiptap.chain().focus().toggleOrderedList().run();

        this.updateToolbar();
    }

    // =========================================================
    // Bloc de citation
    // =========================================================

    toggleBlockquote() {
        this.tiptap
            .chain()
            .focus()
            .toggleBlockquote()
            .run();

        this.updateToolbar();
    }

    // =========================================================
    // Bloc de code
    // =========================================================

    toggleCodeBlock() {
        this.tiptap
            .chain()
            .focus()
            .toggleCodeBlock()
            .run();

        this.updateToolbar();
    }

    // =========================================================
    // Lien
    // =========================================================

    setLink() {
        const previousUrl =
            this.tiptap.getAttributes("link").href;

        const url = window.prompt(
            "URL du lien :",
            previousUrl || "https://",
        );

        if (url === null) {
            return;
        }

        if (url === "") {
            this.tiptap
                .chain()
                .focus()
                .unsetLink()
                .run();

            this.updateToolbar();

            return;
        }

        this.tiptap
            .chain()
            .focus()
            .setLink({
                href: url,
            })
            .run();

        this.updateToolbar();
    }

    removeLink() {
        this.tiptap
            .chain()
            .focus()
            .unsetLink()
            .run();

        this.updateToolbar();
    }

    // =========================================================
    // Couleur
    // =========================================================

    setColor(event) {
        const color = event.currentTarget.value;

        this.tiptap.chain().focus().setColor(color).run();

        this.updateToolbar();
    }

    removeColor() {
        this.tiptap
            .chain()
            .focus()
            .unsetColor()
            .run();

        this.updateToolbar();
    }

    // =========================================================
    // Alignement
    // =========================================================

    setAlignLeft() {
        this.tiptap
            .chain()
            .focus()
            .setTextAlign("left")
            .run();

        this.updateToolbar();
    }

    setAlignCenter() {
        this.tiptap
            .chain()
            .focus()
            .setTextAlign("center")
            .run();

        this.updateToolbar();
    }

    setAlignRight() {
        this.tiptap
            .chain()
            .focus()
            .setTextAlign("right")
            .run();

        this.updateToolbar();
    }

    setAlignJustify() {
        this.tiptap
            .chain()
            .focus()
            .setTextAlign("justify")
            .run();

        this.updateToolbar();
    }

    // =========================================================
    // Ligne horizontale
    // =========================================================

    setHorizontalRule() {
        this.tiptap
            .chain()
            .focus()
            .setHorizontalRule()
            .run();

        this.updateToolbar();
    }

    // =========================================================
    // Formatage
    // =========================================================

    clearFormat() {
        this.tiptap
            .chain()
            .focus()
            .clearNodes()
            .unsetAllMarks()
            .run();

        this.updateToolbar();
    }

    // =========================================================
    // Undo / Redo
    // =========================================================

    undo() {
        this.tiptap
            .chain()
            .focus()
            .undo()
            .run();

        this.updateToolbar();
    }

    redo() {
        this.tiptap
            .chain()
            .focus()
            .redo()
            .run();

        this.updateToolbar();
    }

    // =========================================================
    // Toolbar state
    // =========================================================

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
            this.codeButtonTarget,
            this.tiptap.isActive("code"),
        );

        this.setButtonState(
            this.paragraphButtonTarget,
            this.tiptap.isActive("paragraph"),
        );

        this.setButtonState(
            this.bulletListButtonTarget,
            this.tiptap.isActive("bulletList"),
        );

        this.setButtonState(
            this.orderedListButtonTarget,
            this.tiptap.isActive("orderedList"),
        );

        this.setButtonState(
            this.blockquoteButtonTarget,
            this.tiptap.isActive("blockquote"),
        );

        this.setButtonState(
            this.codeBlockButtonTarget,
            this.tiptap.isActive("codeBlock"),
        );

        this.setButtonState(
            this.linkButtonTarget,
            this.tiptap.isActive("link"),
        );

        this.setButtonState(
            this.alignLeftButtonTarget,
            this.tiptap.isActive({
                textAlign: "left",
            }),
        );

        this.setButtonState(
            this.alignCenterButtonTarget,
            this.tiptap.isActive({
                textAlign: "center",
            }),
        );

        this.setButtonState(
            this.alignRightButtonTarget,
            this.tiptap.isActive({
                textAlign: "right",
            }),
        );

        this.setButtonState(
            this.alignJustifyButtonTarget,
            this.tiptap.isActive({
                textAlign: "justify",
            }),
        );

        this.updateHeadingSelect();
    }

    updateHeadingSelect() {
        if (!this.hasHeadingTarget) {
            return;
        }

        let value = "0";

        for (let level = 1; level <= 6; level++) {
            if (
                this.tiptap.isActive("heading", {
                    level,
                })
            ) {
                value = String(level);
                break;
            }
        }

        this.headingTarget.value = value;
    }

    setButtonState(button, active) {
        button.classList.toggle("bg-cyan-500", active);

        button.classList.toggle("text-slate-950", active);

        button.classList.toggle("bg-gray-800", !active);

        button.classList.toggle("text-gray-300", !active);
    }

    // =========================================================
    // JSON
    // =========================================================

    getJSON() {
        return this.tiptap.getJSON();
    }

    getHTML() {
        return this.tiptap.getHTML();
    }

    disconnect() {
        if (this.tiptap) {
            this.tiptap.destroy();
        }
    }
}