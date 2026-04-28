import * as React from "react"
import { cva, type VariantProps } from "class-variance-authority"
import { Slot } from "radix-ui"

import { cn } from "@/lib/utils"

const badgeVariants = cva(
  "apbl:inline-flex apbl:w-fit apbl:shrink-0 apbl:items-center apbl:justify-center apbl:gap-1 apbl:overflow-hidden apbl:rounded-full apbl:border apbl:border-transparent apbl:px-2 apbl:py-0.5 apbl:text-xs apbl:font-medium apbl:whitespace-nowrap apbl:transition-[color,box-shadow] apbl:focus-visible:border-ring apbl:focus-visible:ring-[3px] apbl:focus-visible:ring-ring/50 apbl:aria-invalid:border-destructive apbl:aria-invalid:ring-destructive/20 apbl:dark:aria-invalid:ring-destructive/40 apbl:[&>svg]:pointer-events-none apbl:[&>svg]:size-3",
  {
    variants: {
      variant: {
        default: "apbl:bg-primary apbl:text-primary-foreground apbl:[a&]:hover:bg-primary/90",
        secondary:
          "apbl:bg-secondary apbl:text-secondary-foreground apbl:[a&]:hover:bg-secondary/90",
        destructive:
          "apbl:bg-destructive apbl:text-white apbl:focus-visible:ring-destructive/20 apbl:dark:bg-destructive/60 apbl:dark:focus-visible:ring-destructive/40 apbl:[a&]:hover:bg-destructive/90",
        outline:
          "apbl:border-border apbl:text-foreground apbl:[a&]:hover:bg-accent apbl:[a&]:hover:text-accent-foreground",
        ghost: "apbl:[a&]:hover:bg-accent apbl:[a&]:hover:text-accent-foreground",
        link: "apbl:text-primary apbl:underline-offset-4 apbl:[a&]:hover:underline",
      },
    },
    defaultVariants: {
      variant: "default",
    },
  }
)

function Badge({
  className,
  variant = "default",
  asChild = false,
  ...props
}: React.ComponentProps<"span"> &
  VariantProps<typeof badgeVariants> & { asChild?: boolean }) {
  const Comp = asChild ? Slot.Root : "span"

  return (
    <Comp
      data-slot="badge"
      data-variant={variant}
      className={cn(badgeVariants({ variant }), className)}
      {...props}
    />
  )
}

export { Badge, badgeVariants }
