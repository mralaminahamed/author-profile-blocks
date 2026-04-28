import * as React from "react"
import { cva, type VariantProps } from "class-variance-authority"
import { Slot } from "radix-ui"

import { cn } from "@/lib/utils"

const buttonVariants = cva(
  "apbl:inline-flex apbl:shrink-0 apbl:items-center apbl:justify-center apbl:gap-2 apbl:rounded-md apbl:text-sm apbl:font-medium apbl:whitespace-nowrap apbl:transition-all apbl:outline-none apbl:focus-visible:border-ring apbl:focus-visible:ring-[3px] apbl:focus-visible:ring-ring/50 apbl:disabled:pointer-events-none apbl:disabled:opacity-50 apbl:aria-invalid:border-destructive apbl:aria-invalid:ring-destructive/20 apbl:dark:aria-invalid:ring-destructive/40 apbl:[&_svg]:pointer-events-none apbl:[&_svg]:shrink-0 apbl:[&_svg:not([class*=size-])]:size-4",
  {
    variants: {
      variant: {
        default: "apbl:bg-primary apbl:text-primary-foreground apbl:hover:bg-primary/90",
        destructive:
          "apbl:bg-destructive apbl:text-white apbl:hover:bg-destructive/90 apbl:focus-visible:ring-destructive/20 apbl:dark:bg-destructive/60 apbl:dark:focus-visible:ring-destructive/40",
        outline:
          "apbl:border apbl:bg-background apbl:shadow-xs apbl:hover:bg-accent apbl:hover:text-accent-foreground apbl:dark:border-input apbl:dark:bg-input/30 apbl:dark:hover:bg-input/50",
        secondary:
          "apbl:bg-secondary apbl:text-secondary-foreground apbl:hover:bg-secondary/80",
        ghost:
          "apbl:hover:bg-accent apbl:hover:text-accent-foreground apbl:dark:hover:bg-accent/50",
        link: "apbl:text-primary apbl:underline-offset-4 apbl:hover:underline",
      },
      size: {
        default: "apbl:h-9 apbl:px-4 apbl:py-2 apbl:has-[>svg]:px-3",
        xs: "apbl:h-6 apbl:gap-1 apbl:rounded-md apbl:px-2 apbl:text-xs apbl:has-[>svg]:px-1.5 apbl:[&_svg:not([class*=size-])]:size-3",
        sm: "apbl:h-8 apbl:gap-1.5 apbl:rounded-md apbl:px-3 apbl:has-[>svg]:px-2.5",
        lg: "apbl:h-10 apbl:rounded-md apbl:px-6 apbl:has-[>svg]:px-4",
        icon: "apbl:size-9",
        "icon-xs": "apbl:size-6 apbl:rounded-md apbl:[&_svg:not([class*=size-])]:size-3",
        "icon-sm": "apbl:size-8",
        "icon-lg": "apbl:size-10",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  }
)

function Button({
  className,
  variant = "default",
  size = "default",
  asChild = false,
  ...props
}: React.ComponentProps<"button"> &
  VariantProps<typeof buttonVariants> & {
    asChild?: boolean
  }) {
  const Comp = asChild ? Slot.Root : "button"

  return (
    <Comp
      data-slot="button"
      data-variant={variant}
      data-size={size}
      className={cn(buttonVariants({ variant, size, className }))}
      {...props}
    />
  )
}

export { Button, buttonVariants }
