import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["container", "svg", "trackPath", "progressPath", "entry", "marker"];

    connect() {
        this.frameId = null;
        this.reducedMotion = window.matchMedia?.("(prefers-reduced-motion: reduce)").matches ?? false;
        this.scheduleUpdate = () => {
            if (this.frameId !== null) return;

            this.frameId = window.requestAnimationFrame(() => {
                this.frameId = null;
                this.updateTimeline();
            });
        };

        if ("IntersectionObserver" in window && !this.reducedMotion) {
            this.element.dataset.careerTimelineEnhanced = "true";
            this.entryObserver = new IntersectionObserver(
                (items) => {
                    items.forEach((item) => {
                        if (!item.isIntersecting) return;

                        item.target.classList.add("is-visible");
                        this.entryObserver.unobserve(item.target);
                    });
                },
                { threshold: 0.15, rootMargin: "0px 0px -8% 0px" },
            );

            this.entryTargets.forEach((entry) => this.entryObserver.observe(entry));
        } else {
            this.entryTargets.forEach((entry) => entry.classList.add("is-visible"));
        }

        window.addEventListener("scroll", this.scheduleUpdate, { passive: true });
        window.addEventListener("resize", this.scheduleUpdate);

        if ("ResizeObserver" in window) {
            this.resizeObserver = new ResizeObserver(this.scheduleUpdate);
            this.resizeObserver.observe(this.containerTarget);
        }

        this.scheduleUpdate();
    }

    disconnect() {
        window.removeEventListener("scroll", this.scheduleUpdate);
        window.removeEventListener("resize", this.scheduleUpdate);
        this.entryObserver?.disconnect();
        this.resizeObserver?.disconnect();

        if (this.frameId !== null) {
            window.cancelAnimationFrame(this.frameId);
        }
    }

    updateTimeline() {
        this.drawCurve();

        const bounds = this.containerTarget.getBoundingClientRect();
        const progress = this.reducedMotion
            ? 1
            : Math.min(1, Math.max(0, (window.innerHeight - bounds.top) / (window.innerHeight + bounds.height)));
        const length = this.progressPathTarget.getTotalLength();

        this.progressPathTarget.style.strokeDasharray = `${length}`;
        this.progressPathTarget.style.strokeDashoffset = `${length * (1 - progress)}`;
    }

    drawCurve() {
        const bounds = this.svgTarget.getBoundingClientRect();
        const width = Math.max(bounds.width, 1);
        const height = Math.max(bounds.height, 1);
        const points = this.markerTargets.map((marker) => {
            const markerBounds = marker.getBoundingClientRect();

            return {
                x: markerBounds.left + markerBounds.width / 2 - bounds.left,
                y: markerBounds.top + markerBounds.height / 2 - bounds.top,
            };
        });

        if (points.length === 0) return;

        this.svgTarget.setAttribute("viewBox", `0 0 ${width} ${height}`);

        let path = `M ${points[0].x} ${points[0].y}`;

        if (points.length === 1) {
            path += ` L ${points[0].x} ${Math.max(points[0].y + 1, height - 4)}`;
        } else {
            for (let index = 1; index < points.length; index += 1) {
                const previous = points[index - 1];
                const current = points[index];
                const curve = (current.y - previous.y) / 2;

                path += ` C ${previous.x} ${previous.y + curve}, ${current.x} ${current.y - curve}, ${current.x} ${current.y}`;
            }
        }

        this.trackPathTarget.setAttribute("d", path);
        this.progressPathTarget.setAttribute("d", path);
    }
}
